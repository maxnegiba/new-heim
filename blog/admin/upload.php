<?php
// Disable all error output
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffering to prevent any accidental output
ob_start();

session_start();

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    ob_clean();
    header('Content-Type: application/json');
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

// Include database
require_once __DIR__ . '/../../db.php';

// Clean any output that might have occurred
ob_clean();

// Set JSON header
header('Content-Type: application/json');

// Configuration
$uploadDir = realpath(__DIR__ . '/../../uploads/blog');
if (!$uploadDir) {
    die(json_encode(['error' => 'Upload directory not found']));
}
$uploadDir .= '/';
$uploadUrl = '/uploads/blog/';

// Create directories if needed
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

foreach (['images', 'videos', 'thumbnails'] as $subdir) {
    $path = $uploadDir . $subdir;
    if (!is_dir($path)) {
        @mkdir($path, 0755, true);
    }
}

// Get action
$action = $_POST['action'] ?? $_GET['action'] ?? 'upload';

try {
    switch ($action) {
        case 'upload':
            handleFileUpload();
            break;
        case 'list':
            listMedia();
            break;
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    ob_clean();
    echo json_encode(['error' => $e->getMessage()]);
}

function handleFileUpload() {
    global $uploadDir, $uploadUrl;
    
    // Check file
    if (!isset($_FILES['file'])) {
        throw new Exception('No file uploaded');
    }
    
    $file = $_FILES['file'];
    
    // Check upload error
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            1 => 'File too large (server limit)',
            2 => 'File too large (form limit)',
            3 => 'Partial upload',
            4 => 'No file',
            6 => 'No temp folder',
            7 => 'Write failed',
            8 => 'Extension error'
        ];
        throw new Exception($errors[$file['error']] ?? 'Upload error ' . $file['error']);
    }
    
    // Check size (10MB)
    if ($file['size'] > 10485760) {
        throw new Exception('File too large. Max 10MB');
    }
    
    // Check extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm', 'ogg'];
    
    if (!in_array($ext, $allowed)) {
        throw new Exception('File type not allowed');
    }
    
    // Determine type and directory
    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($ext, $imageExts)) {
        $subdir = 'images/';
        $type = 'image';
    } else {
        $subdir = 'videos/';
        $type = 'video';
    }
    
    // Generate filename
    $newName = date('Ymd_His') . '_' . uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $subdir . $newName;
    $publicUrl = $uploadUrl . $subdir . $newName;
    
    // Move file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Failed to save file');
    }
    
    // Success response
    ob_clean();
    echo json_encode([
        'success' => true,
        'url' => $publicUrl,
        'type' => $type,
        'name' => $file['name']
    ]);
    exit;
}

function listMedia() {
    global $uploadDir, $uploadUrl;
    
    $media = [];
    
    // Scan images
    $imgDir = $uploadDir . 'images/';
    if (is_dir($imgDir)) {
        $files = scandir($imgDir);
        foreach ($files as $file) {
            if ($file[0] === '.') continue;
            $media[] = [
                'url' => $uploadUrl . 'images/' . $file,
                'type' => 'image',
                'name' => $file
            ];
        }
    }
    
    // Scan videos
    $vidDir = $uploadDir . 'videos/';
    if (is_dir($vidDir)) {
        $files = scandir($vidDir);
        foreach ($files as $file) {
            if ($file[0] === '.') continue;
            $media[] = [
                'url' => $uploadUrl . 'videos/' . $file,
                'type' => 'video',
                'name' => $file
            ];
        }
    }
    
    ob_clean();
    echo json_encode([
        'success' => true,
        'media' => $media
    ]);
    exit;
}
?>