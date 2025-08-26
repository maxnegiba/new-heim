<?php
session_start();

// Check if logged in
if (!isset($_SESSION['admin_logged_in'])) {
    die('Not logged in');
}

// Show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Server Configuration Test</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "\nUpload Directory Tests:\n";

$uploadDir = __DIR__ . '/../../uploads/blog/';
echo "Upload dir path: " . $uploadDir . "\n";
echo "Upload dir exists: " . (is_dir($uploadDir) ? 'YES' : 'NO') . "\n";
echo "Upload dir writable: " . (is_writable($uploadDir) ? 'YES' : 'NO') . "\n";

// Try to create directories
$dirs = ['images', 'videos', 'thumbnails'];
foreach ($dirs as $dir) {
    $path = $uploadDir . $dir;
    if (!is_dir($path)) {
        if (@mkdir($path, 0755, true)) {
            echo "Created: $dir/\n";
        } else {
            echo "FAILED to create: $dir/\n";
        }
    } else {
        echo "Exists: $dir/ (writable: " . (is_writable($path) ? 'YES' : 'NO') . ")\n";
    }
}

echo "</pre>";

// Simple upload form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test'])) {
    echo "<h3>Upload Test Results:</h3><pre>";
    
    $file = $_FILES['test'];
    echo "File received: " . $file['name'] . "\n";
    echo "File size: " . $file['size'] . " bytes\n";
    echo "File error code: " . $file['error'] . "\n";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $targetPath = $uploadDir . 'test_' . time() . '_' . $file['name'];
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo "SUCCESS! File uploaded to: " . $targetPath . "\n";
            // Clean up test file
            unlink($targetPath);
            echo "Test file removed.\n";
        } else {
            echo "FAILED to move file. Check directory permissions.\n";
        }
    } else {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
        ];
        echo "Upload error: " . ($errors[$file['error']] ?? 'Unknown error') . "\n";
    }
    echo "</pre>";
}
?>

<h3>Test Upload Form</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test" required>
    <button type="submit">Test Upload</button>
</form>

<p><a href="/blog/admin/dashboard.php">Back to Dashboard</a></p>