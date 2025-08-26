<?php
// check-permissions.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) die('Not logged in');

$uploadDir = realpath(__DIR__ . '/../../uploads/blog');
echo "<pre>";
echo "Upload directory: $uploadDir\n";
echo "Exists: " . (is_dir($uploadDir) ? 'YES' : 'NO') . "\n";
echo "Writable: " . (is_writable($uploadDir) ? 'YES' : 'NO') . "\n";
echo "Permissions: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "\n";
echo "\nSubdirectories:\n";

foreach (['images', 'videos', 'thumbnails'] as $dir) {
    $path = $uploadDir . '/' . $dir;
    echo "$dir/: ";
    if (is_dir($path)) {
        echo "EXISTS, ";
        echo is_writable($path) ? "WRITABLE" : "NOT WRITABLE";
        echo ", perms: " . substr(sprintf('%o', fileperms($path)), -4);
    } else {
        echo "DOES NOT EXIST";
    }
    echo "\n";
}
echo "</pre>";
?>