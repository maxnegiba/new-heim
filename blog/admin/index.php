<?php
session_start();
require_once __DIR__ . './db.php';

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /blog/admin");
    exit;
}

// Process login
if (!isset($_SESSION['admin']) && isset($_POST['login'])) {
    $username = trim($_POST['u'] ?? '');
    $password = $_POST['p'] ?? '';
    
    try {
        // Check database for user
        $stmt = $pdo->prepare("SELECT * FROM blog_admins WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            header("Location: /blog/admin/dashboard.php");
            exit;
        } else {
            $error = 'Invalid credentials';
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

// Check if already logged in
if (isset($_SESSION['admin'])) {
    header("Location: /blog/admin/dashboard.php");
    exit;
}

$page_title = 'Admin Login | Blog';
include '../includes/blog-header.php';
?>

<!-- Your existing HTML/CSS here -->

<div class="admin-login-container">
    <div class="admin-login-form">
        <h2>Blog Admin Login</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <input type="text" name="u" placeholder="Username" class="admin-input" required>
            <input type="password" name="p" placeholder="Password" class="admin-input" required>
            <button type="submit" name="login" class="admin-btn">Login</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>