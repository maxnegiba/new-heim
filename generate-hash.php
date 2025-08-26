<?php
// Generează hash pentru parola "roof2025"
$password = 'roof2025';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash-ul corect este: " . $hash;
?>