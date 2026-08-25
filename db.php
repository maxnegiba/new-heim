<?php
$pdo = null;

$dbHost = getenv('MB_DB_HOST') ?: '';
$dbName = getenv('MB_DB_NAME') ?: '';
$dbUser = getenv('MB_DB_USER') ?: '';
$dbPass = getenv('MB_DB_PASS') ?: '';

if ($dbHost !== '' && $dbName !== '' && $dbUser !== '') {
    try {
        $pdo = new PDO(
            "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
            $dbUser,
            $dbPass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $exception) {
        error_log('MB Bau blog database connection failed: ' . $exception->getMessage());
        $pdo = null;
    }
}
