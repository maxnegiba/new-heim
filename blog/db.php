<?php
// db.php
$host = 'localhost';
$db   = 'hausmeister_blog';
$user = 'hausmeister_user';
$pass = 'Par0laPuternic@';
$pdo  = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
?>