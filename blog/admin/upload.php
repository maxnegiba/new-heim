<?php
session_start();
require_once __DIR__ . '/../../db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

// Configuration
$uploadDir = __DIR__ . '/../../uploads/blog/';
$uploadUrl = '/uploads/blog/';
$maxFileSize = 10 * 1024 * 1024; // 10MB
$allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$allowedVideoTypes = ['mp4', 'webm', 'ogg'];

// Create upload directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
    // Create subdirectories
    mkdir($uploadDir . 'images/', 0755, true);
    mkdir($uploadDir . 'videos/', 0755, true);
    mkdir($uploadDir . 'thumbnails/', 0755, true);
}

// Handle different upload types
$action = $_POST['action'] ?? $_GET['action'] ?? 'upload';

switch ($action) {
    case 'upload':
        handleFileUpload();
        break;
    case 'list':
        listMedia();
        break;
    case 'delete':
        deleteMedia();
        break;
    default:
        http_response_code(400);
        die(json_encode(['error' => 'Invalid action']));
}

function handleFileUpload() {
    global $uploadDir, $uploadUrl, $maxFileSize, $allowedImageTypes, $allowedVideoTypes, $pdo;
    
    if (!isset($_FILES['file'])) {
        die(json_encode(['error' => 'No file uploaded']));
    }
    
    $file = $_FILES['file'];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die(json_encode(['error' => 'Upload failed with error code: ' . $file['error']]));
    }
    
    // Check file size
    if ($file['size'] > $maxFileSize) {
        die(json_encode(['error' => 'File too large. Maximum size is ' . ($maxFileSize / 1024 / 1024) . 'MB']));
    }
    
    // Get file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    
    // Determine file type and subdirectory
    if (in_array($extension, $allowedImageTypes)) {
        $fileType = 'image';
        $subDir = 'images/';
    } elseif (in_array($extension, $allowedVideoTypes)) {
        $fileType = 'video';
        $subDir = 'videos/';
    } else {
        die(json_encode(['error' => 'File type not allowed']));
    }
    
    // Generate unique filename
    $uniqueName = date('Y-m-d') . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9-_]/', '', $originalName) . '.' . $extension;
    $targetPath = $uploadDir . $subDir . $uniqueName;
    $publicUrl = $uploadUrl . $subDir . $uniqueName;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        die(json_encode(['error' => 'Failed to move uploaded file']));
    }
    
    // Create thumbnail for images
    $thumbnailUrl = null;
    if ($fileType === 'image') {
        $thumbnailUrl = createThumbnail($targetPath, $uniqueName);
    }
    
    // Get file dimensions for images
    $width = null;
    $height = null;
    if ($fileType === 'image') {
        list($width, $height) = getimagesize($targetPath);
    }
    
    // Save to database (optional - you can create a media table)
    try {
        // Check if media table exists
        $tableExists = $pdo->query("SHOW TABLES LIKE 'blog_media'")->rowCount() > 0;
        
        if ($tableExists) {
            $stmt = $pdo->prepare("
                INSERT INTO blog_media (filename, original_name, mime_type, file_size, file_type, url, thumbnail_url, width, height, uploaded_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $uniqueName,
                $file['name'],
                $file['type'],
                $file['size'],
                $fileType,
                $publicUrl,
                $thumbnailUrl,
                $width,
                $height,
                $_SESSION['admin_username'] ?? 'admin'
            ]);
            
            $mediaId = $pdo->lastInsertId();
        } else {
            $mediaId = null;
        }
    } catch (PDOException $e) {
        // Media table doesn't exist, continue without database storage
        $mediaId = null;
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'id' => $mediaId,
        'url' => $publicUrl,
        'thumbnail' => $thumbnailUrl,
        'type' => $fileType,
        'name' => $file['name'],
        'size' => $file['size'],
        'width' => $width,
        'height' => $height
    ]);
}

function createThumbnail($sourcePath, $filename) {
    global $uploadDir, $uploadUrl;
    
    $thumbnailDir = $uploadDir . 'thumbnails/';
    $thumbnailPath = $thumbnailDir . 'thumb_' . $filename;
    $thumbnailUrl = $uploadUrl . 'thumbnails/thumb_' . $filename;
    
    // Get image info
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return null;
    }
    
    $sourceWidth = $imageInfo[0];
    $sourceHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];
    
    // Calculate thumbnail dimensions (max 300px)
    $maxSize = 300;
    if ($sourceWidth > $sourceHeight) {
        $thumbWidth = $maxSize;
        $thumbHeight = floor($sourceHeight * ($maxSize / $sourceWidth));
    } else {
        $thumbHeight = $maxSize;
        $thumbWidth = floor($sourceWidth * ($maxSize / $sourceHeight));
    }
    
    // Create source image resource
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case 'image/webp':
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            return null;
    }
    
    if (!$sourceImage) {
        return null;
    }
    
    // Create thumbnail
    $thumbImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
    
    // Preserve transparency for PNG and GIF
    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
        imagecolortransparent($thumbImage, imagecolorallocate($thumbImage, 0, 0, 0));
        imagealphablending($thumbImage, false);
        imagesavealpha($thumbImage, true);
    }
    
    // Resize image
    imagecopyresampled(
        $thumbImage, $sourceImage,
        0, 0, 0, 0,
        $thumbWidth, $thumbHeight,
        $sourceWidth, $sourceHeight
    );
    
    // Save thumbnail
    switch ($mimeType) {
        case 'image/jpeg':
            imagejpeg($thumbImage, $thumbnailPath, 85);
            break;
        case 'image/png':
            imagepng($thumbImage, $thumbnailPath, 8);
            break;
        case 'image/gif':
            imagegif($thumbImage, $thumbnailPath);
            break;
        case 'image/webp':
            imagewebp($thumbImage, $thumbnailPath, 85);
            break;
    }
    
    // Clean up
    imagedestroy($sourceImage);
    imagedestroy($thumbImage);
    
    return $thumbnailUrl;
}

function listMedia() {
    global $pdo, $uploadDir, $uploadUrl;
    
    $type = $_GET['type'] ?? 'all';
    $page = intval($_GET['page'] ?? 1);
    $perPage = 20;
    $offset = ($page - 1) * $perPage;
    
    $media = [];
    
    // Try to get from database first
    try {
        $tableExists = $pdo->query("SHOW TABLES LIKE 'blog_media'")->rowCount() > 0;
        
        if ($tableExists) {
            $where = '';
            if ($type !== 'all') {
                $where = "WHERE file_type = :type";
            }
            
            $stmt = $pdo->prepare("
                SELECT * FROM blog_media 
                $where 
                ORDER BY created_at DESC 
                LIMIT :offset, :limit
            ");
            
            if ($type !== 'all') {
                $stmt->bindParam(':type', $type);
            }
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
            $stmt->execute();
            
            $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        // Fall back to file system scanning
        $media = scanMediaDirectory($type);
    }
    
    // If no database, scan directory
    if (empty($media)) {
        $media = scanMediaDirectory($type);
    }
    
    echo json_encode([
        'success' => true,
        'media' => $media,
        'page' => $page,
        'per_page' => $perPage
    ]);
}

function scanMediaDirectory($type = 'all') {
    global $uploadDir, $uploadUrl, $allowedImageTypes, $allowedVideoTypes;
    
    $media = [];
    $dirs = [];
    
    if ($type === 'all' || $type === 'image') {
        $dirs[] = ['path' => $uploadDir . 'images/', 'url' => $uploadUrl . 'images/', 'type' => 'image'];
    }
    if ($type === 'all' || $type === 'video') {
        $dirs[] = ['path' => $uploadDir . 'videos/', 'url' => $uploadUrl . 'videos/', 'type' => 'video'];
    }
    
    foreach ($dirs as $dir) {
        if (is_dir($dir['path'])) {
            $files = scandir($dir['path']);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $allowedTypes = $dir['type'] === 'image' ? $allowedImageTypes : $allowedVideoTypes;
                
                if (in_array($extension, $allowedTypes)) {
                    $filePath = $dir['path'] . $file;
                    $media[] = [
                        'url' => $dir['url'] . $file,
                        'type' => $dir['type'],
                        'name' => $file,
                        'size' => filesize($filePath),
                        'modified' => filemtime($filePath)
                    ];
                }
            }
        }
    }
    
    // Sort by modified date, newest first
    usort($media, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    return $media;
}

function deleteMedia() {
    global $uploadDir, $pdo;
    
    $mediaId = $_POST['id'] ?? null;
    $url = $_POST['url'] ?? null;
    
    if (!$mediaId && !$url) {
        die(json_encode(['error' => 'No media specified']));
    }
    
    // Delete from database if ID provided
    if ($mediaId) {
        try {
            $stmt = $pdo->prepare("SELECT url, thumbnail_url FROM blog_media WHERE id = ?");
            $stmt->execute([$mediaId]);
            $media = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($media) {
                // Delete files
                $filePath = str_replace('/uploads/blog/', $uploadDir, $media['url']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                if ($media['thumbnail_url']) {
                    $thumbPath = str_replace('/uploads/blog/', $uploadDir, $media['thumbnail_url']);
                    if (file_exists($thumbPath)) {
                        unlink($thumbPath);
                    }
                }
                
                // Delete from database
                $stmt = $pdo->prepare("DELETE FROM blog_media WHERE id = ?");
                $stmt->execute([$mediaId]);
            }
        } catch (PDOException $e) {
            // Continue even if database fails
        }
    }
    
    // Delete by URL
    if ($url) {
        $filePath = str_replace('/uploads/blog/', $uploadDir, $url);
        if (file_exists($filePath)) {
            unlink($filePath);
            
            // Also delete thumbnail if it exists
            $filename = basename($filePath);
            $thumbPath = $uploadDir . 'thumbnails/thumb_' . $filename;
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
        }
    }
    
    echo json_encode(['success' => true]);
}
?>