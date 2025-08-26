<?php
session_start();
require_once __DIR__ . '/../../db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$uploadDir = realpath(__DIR__ . '/../../uploads/blog') . '/';
$uploadUrl = '/uploads/blog/';

// Create upload directory if it doesn't exist
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        die(json_encode(['error' => 'Cannot create upload directory: ' . $uploadDir]));
    }
}

// Create subdirectories
$subdirs = ['images', 'videos', 'thumbnails'];
foreach ($subdirs as $subdir) {
    $path = $uploadDir . $subdir . '/';
    if (!is_dir($path)) {
        @mkdir($path, 0755, true);
    }
}

// Handle different actions
$action = $_POST['action'] ?? $_GET['action'] ?? 'upload';

if ($action === 'upload') {
    handleFileUpload();
} elseif ($action === 'list') {
    listMedia();
} else {
    die(json_encode(['error' => 'Invalid action']));
}

function handleFileUpload() {
    global $uploadDir, $uploadUrl, $pdo;
    
    // Check if file was uploaded
    if (!isset($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
        die(json_encode(['error' => 'No file uploaded']));
    }
    
    $file = $_FILES['file'];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            1 => 'File exceeds upload_max_filesize (' . ini_get('upload_max_filesize') . ')',
            2 => 'File exceeds MAX_FILE_SIZE',
            3 => 'File was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing temporary folder',
            7 => 'Failed to write file to disk',
            8 => 'Upload stopped by extension'
        ];
        die(json_encode(['error' => $errors[$file['error']] ?? 'Unknown error: ' . $file['error']]));
    }
    
    // Check file size (10MB max)
    $maxSize = 10 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        die(json_encode(['error' => 'File too large. Maximum size is 10MB']));
    }
    
    // Get file info
    $originalName = $file['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    
    // Check file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm', 'ogg'];
    if (!in_array($extension, $allowedTypes)) {
        die(json_encode(['error' => 'File type not allowed: .' . $extension]));
    }
    
    // Determine subdirectory
    $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $videoTypes = ['mp4', 'webm', 'ogg'];
    
    if (in_array($extension, $imageTypes)) {
        $subDir = 'images/';
        $fileType = 'image';
    } elseif (in_array($extension, $videoTypes)) {
        $subDir = 'videos/';
        $fileType = 'video';
    } else {
        $subDir = '';
        $fileType = 'file';
    }
    
    // Generate unique filename
    $safeName = preg_replace('/[^a-zA-Z0-9-_.]/', '', $originalName);
    $uniqueName = date('Ymd_His') . '_' . uniqid() . '_' . $safeName;
    $targetPath = $uploadDir . $subDir . $uniqueName;
    $publicUrl = $uploadUrl . $subDir . $uniqueName;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        die(json_encode([
            'error' => 'Failed to save file',
            'debug' => [
                'target' => $targetPath,
                'writable' => is_writable($uploadDir . $subDir)
            ]
        ]));
    }
    
    // Try to create thumbnail for images
    $thumbnailUrl = null;
    if ($fileType === 'image' && extension_loaded('gd')) {
        $thumbnailUrl = createSimpleThumbnail($targetPath, $uniqueName, $uploadDir, $uploadUrl);
    }
    
    // Save to database (optional)
    try {
        // Check if table exists
        $stmt = $pdo->query("SHOW TABLES LIKE 'blog_media'");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->prepare("
                INSERT INTO blog_media (filename, original_name, mime_type, file_size, file_type, url, thumbnail_url, uploaded_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $uniqueName,
                $originalName,
                $file['type'],
                $file['size'],
                $fileType,
                $publicUrl,
                $thumbnailUrl,
                $_SESSION['admin_username'] ?? 'admin'
            ]);
        }
    } catch (Exception $e) {
        // Ignore database errors - file is already uploaded
    }
    
    // Return success
    echo json_encode([
        'success' => true,
        'url' => $publicUrl,
        'thumbnail' => $thumbnailUrl ?: $publicUrl,
        'type' => $fileType,
        'name' => $originalName,
        'size' => $file['size']
    ]);
}

function createSimpleThumbnail($sourcePath, $filename, $uploadDir, $uploadUrl) {
    try {
        $imageInfo = @getimagesize($sourcePath);
        if (!$imageInfo) return null;
        
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        
        // Skip if already small
        if ($sourceWidth <= 300 && $sourceHeight <= 300) {
            return null;
        }
        
        // Calculate thumbnail size
        $maxSize = 300;
        if ($sourceWidth > $sourceHeight) {
            $thumbWidth = $maxSize;
            $thumbHeight = intval($sourceHeight * ($maxSize / $sourceWidth));
        } else {
            $thumbHeight = $maxSize;
            $thumbWidth = intval($sourceWidth * ($maxSize / $sourceHeight));
        }
        
        // Create source image
        switch ($imageInfo['mime']) {
            case 'image/jpeg':
                $source = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $source = @imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $source = @imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $source = @imagecreatefromwebp($sourcePath);
                break;
            default:
                return null;
        }
        
        if (!$source) return null;
        
        // Create thumbnail
        $thumb = @imagecreatetruecolor($thumbWidth, $thumbHeight);
        if (!$thumb) {
            imagedestroy($source);
            return null;
        }
        
        // Preserve transparency
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        
        // Resize
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $sourceWidth, $sourceHeight);
        
        // Save thumbnail
        $thumbPath = $uploadDir . 'thumbnails/thumb_' . $filename;
        $success = false;
        
        switch ($imageInfo['mime']) {
            case 'image/jpeg':
                $success = @imagejpeg($thumb, $thumbPath, 85);
                break;
            case 'image/png':
                $success = @imagepng($thumb, $thumbPath, 8);
                break;
            case 'image/gif':
                $success = @imagegif($thumb, $thumbPath);
                break;
            case 'image/webp':
                $success = @imagewebp($thumb, $thumbPath, 85);
                break;
        }
        
        imagedestroy($source);
        imagedestroy($thumb);
        
        return $success ? $uploadUrl . 'thumbnails/thumb_' . $filename : null;
        
    } catch (Exception $e) {
        return null;
    }
}

function listMedia() {
    global $uploadDir, $uploadUrl;
    
    $media = [];
    
    // Scan images directory
    $imagesDir = $uploadDir . 'images/';
    if (is_dir($imagesDir)) {
        $files = @scandir($imagesDir);
        if ($files) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $thumbPath = $uploadDir . 'thumbnails/thumb_' . $file;
                $thumbnail = file_exists($thumbPath) ? $uploadUrl . 'thumbnails/thumb_' . $file : null;
                
                $media[] = [
                    'url' => $uploadUrl . 'images/' . $file,
                    'thumbnail' => $thumbnail ?: $uploadUrl . 'images/' . $file,
                    'type' => 'image',
                    'name' => $file
                ];
            }
        }
    }
    
    // Scan videos directory
    $videosDir = $uploadDir . 'videos/';
    if (is_dir($videosDir)) {
        $files = @scandir($videosDir);
        if ($files) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $media[] = [
                    'url' => $uploadUrl . 'videos/' . $file,
                    'thumbnail' => null,
                    'type' => 'video',
                    'name' => $file
                ];
            }
        }
    }
    
    // Sort by name (newest first based on timestamp in filename)
    usort($media, function($a, $b) {
        return strcmp($b['name'], $a['name']);
    });
    
    echo json_encode([
        'success' => true,
        'media' => $media
    ]);
}
?>