<?php
session_start();

// Configurații admin (în producție, folosește baza de date)
const ADMIN_USER = 'michael';
const ADMIN_PASS_HASH = '$2y$12$bkb7vbFQ0FZbTjvMMREOe.Hkl/M0kS5shCjf0Fgb2QoFHoHf7D4n6'; // parola: roof2025

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /blog/admin");
    exit;
}

// Procesare login
if (!isset($_SESSION['admin']) && isset($_POST['login'])) {
    $username = trim($_POST['u'] ?? '');
    $password = $_POST['p'] ?? '';
    
    if ($username === ADMIN_USER && password_verify($password, ADMIN_PASS_HASH)) {
        $_SESSION['admin'] = true;
        header("Location: /blog/admin/dashboard.php");
        exit;
    } else {
        $error = 'Ungültige Anmeldedaten';
    }
}

// Verificare autentificare pentru dashboard
if (isset($_GET['page']) && $_GET['page'] === 'dashboard' && !isset($_SESSION['admin'])) {
    header("Location: /blog/admin");
    exit;
}

$page_title = 'Admin Login | Blog';
include '../includes/blog-header.php';
?>

<style>
.admin-login-container {
    max-width: 400px;
    margin: 120px auto 60px;
    padding: 0 20px;
}

.admin-login-form {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
}

.admin-login-form h2 {
    color: #2c3e50;
    margin-bottom: 30px;
}

.admin-input {
    width: 100%;
    padding: 15px;
    margin: 15px 0;
    border: 2px solid #eee;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.admin-input:focus {
    border-color: #d32f2f;
    outline: none;
}

.admin-btn {
    width: 100%;
    padding: 15px;
    background: #d32f2f;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-top: 10px;
}

.admin-btn:hover {
    background: #b71c1c;
}

.error-message {
    background: #ffebee;
    color: #c62828;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    border-left: 4px solid #c62828;
}

@media (max-width: 768px) {
    .admin-login-container {
        margin: 100px auto 40px;
    }
    
    .admin-login-form {
        padding: 30px 20px;
    }
}
</style>

<div class="admin-login-container">
    <div class="admin-login-form">
        <h2>Blog Admin Login</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <input type="text" name="u" placeholder="Benutzername" class="admin-input" required>
            <input type="password" name="p" placeholder="Passwort" class="admin-input" required>
            <button type="submit" name="login" class="admin-btn">Anmelden</button>
        </form>
    </div>
</div>

<?php 
// Include footer (assuming it's in the same parent directory as the includes folder)
include __DIR__ . '/../includes/footer.php'; 
?>