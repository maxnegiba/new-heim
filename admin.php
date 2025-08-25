<?php
session_start();
const ADMIN_USER = 'michael';
const ADMIN_PASS = 'roof2025';

if (!isset($_SESSION['admin']) && isset($_POST['login'])) {
    if ($_POST['u'] === ADMIN_USER && $_POST['p'] === ADMIN_PASS) {
        $_SESSION['admin'] = true;
    } else {
        echo '<p>Login falsch</p>';
    }
}

if (!isset($_SESSION['admin'])) {
    echo '<form method="post">
            <input name="u" placeholder="User">
            <input name="p" type="password" placeholder="Pass">
            <button name="login">Login</button>
          </form>';
    exit;
}

require 'db.php';

if ($_POST['save'] ?? false) {
    $title = $_POST['title'];
    $slug  = strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9 ]/', '', $title)));
    $body  = $_POST['body'];
    $stmt  = $pdo->prepare("INSERT INTO blog_posts (title, slug, body) VALUES (?,?,?)");
    $stmt->execute([$title, $slug, $body]);
    header("Location: /blog");
    exit;
}
?>
<form method="post">
  <input name="title" placeholder="Titel" required>
  <textarea name="body" rows="20" required></textarea>
  <button name="save">Speichern</button>
</form>