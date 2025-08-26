<?php
// blog/admin/upload.php
// declare(strict_types=1); // optional

// DEV: uncomment to debug
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

ob_start();
session_start();

// Auth check
if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// DB include
require_once __DIR__ . '/../../db.php';

header('Content-Type: application/json');

// Base paths
$basePath = realpath(__DIR__ . '/../../');
if ($basePath === false) {
    $basePath = __DIR__ . '/../../'; // fallback
}
$uploadDir = rtrim($basePath, DIRECTORY_SEPARATOR) . '/uploads/blog';
$uploadUrl = '/uploads/blog';

// Ensure directories
foreach (['', '/images', '/videos', '/thumbnails'] as $sub) {
    $dir = $uploadDir . $sub;
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Cannot create directory: ' . $dir]);
        exit;
    }
}

$action = $_POST['action'] ?? $_GET['action'] ?? 'upload';

try {
    if ($action === 'upload') {
        handleFileUpload($uploadDir, $uploadUrl);
    } elseif ($action === 'list') {
        listMedia($uploadDir, $uploadUrl);
    } else {
        throw new Exception('Invalid action');
    }
} catch (Throwable $e) {
    ob_clean();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit;

function handleFileUpload(string $uploadDir, string $uploadUrl): void {
    if (!isset($_FILES['file'])) {
        throw new Exception('No file uploaded');
    }
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception(uploadErrorMessage($file['error']));
    }

    // Match your .htaccess (20MB)
    $max = 20 * 1024 * 1024;
    if ($file['size'] > $max) {
        throw new Exception('File too large. Max 20MB');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp','mp4','webm','ogg'];
    if (!in_array($ext, $allowed, true)) {
        throw new Exception('File type not allowed');
    }

    $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp'], true);
    $subdir = $isImage ? 'images' : 'videos';

    $newName = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $targetPath = $uploadDir . '/' . $subdir . '/' . $newName;
    $publicUrl  = $uploadUrl  . '/' . $subdir . '/' . $newName;

    if (!is_writable($uploadDir . '/' . $subdir)) {
        throw new Exception('Upload directory is not writable: ' . $uploadDir . '/' . $subdir);
    }

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Failed to save file (permissions?)');
    }

    ob_clean();
    echo json_encode([
        'success' => true,
        'url' => $publicUrl,
        'type' => $isImage ? 'image' : 'video',
        'name' => $file['name']
    ]);
}

function listMedia(string $uploadDir, string $uploadUrl): void {
    $media = [];
    foreach (['images' => 'image', 'videos' => 'video'] as $folder => $type) {
        $dir = $uploadDir . '/' . $folder;
        if (!is_dir($dir)) continue;
        $files = scandir($dir) ?: [];
        foreach ($files as $file) {
            if ($file[0] === '.') continue;
            $media[] = [
                'url' => $uploadUrl . '/' . $folder . '/' . $file,
                'type' => $type,
                'name' => $file
            ];
        }
    }
    ob_clean();
    echo json_encode(['success' => true, 'media' => $media]);
}

function uploadErrorMessage(int $code): string {
    return [
        UPLOAD_ERR_INI_SIZE   => 'File too large (server limit)',
        UPLOAD_ERR_FORM_SIZE  => 'File too large (form limit)',
        UPLOAD_ERR_PARTIAL    => 'Partial upload',
        UPLOAD_ERR_NO_FILE    => 'No file',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temp folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file',
        UPLOAD_ERR_EXTENSION  => 'Upload blocked by extension'
    ][$code] ?? ('Upload error ' . $code);
}