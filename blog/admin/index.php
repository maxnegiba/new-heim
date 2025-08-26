<?php
// blog/admin/index.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Admin credentials (keeping your working ones)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '1995');

// Additional security
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutes

// Initialize session variables
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = 0;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /blog/admin/");
    exit;
}

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: /blog/admin/dashboard.php");
    exit;
}

// Check for lockout
$is_locked = false;
if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
    if (time() - $_SESSION['lockout_time'] < LOCKOUT_TIME) {
        $is_locked = true;
        $remaining_time = LOCKOUT_TIME - (time() - $_SESSION['lockout_time']);
        $error = "Too many failed attempts. Please wait " . ceil($remaining_time / 60) . " minutes.";
    } else {
        // Reset after lockout period
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_time'] = 0;
    }
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_locked) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Add CSRF protection
    if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Security error. Please try again.';
    } elseif ($username === ADMIN_USER && $password === ADMIN_PASS) {
        // Successful login
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();
        $_SESSION['login_attempts'] = 0;
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        header("Location: /blog/admin/dashboard.php");
        exit;
    } else {
        // Failed login
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $_SESSION['lockout_time'] = time();
        }
        $error = 'Invalid credentials! Attempt ' . $_SESSION['login_attempts'] . ' of ' . MAX_LOGIN_ATTEMPTS;
    }
}

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Blog Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
            backdrop-filter: blur(10px);
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo i {
            font-size: 50px;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            background: white;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }
        
        button:before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.2);
            transition: left 0.5s;
        }
        
        button:hover:before {
            left: 100%;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .error {
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .info {
            background: linear-gradient(135deg, #48c6ef, #6f86d6);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .info strong {
            display: block;
            margin-bottom: 5px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .remember-me input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
        
        .remember-me label {
            margin: 0;
            font-weight: normal;
            text-transform: none;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 12px;
        }
        
        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
        
        .loading i {
            font-size: 30px;
            color: #667eea;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-shield-alt"></i>
        </div>
        
        <h1>Admin Access</h1>
        <p class="subtitle">Hausmeister Michael Blog Management</p>
        
        <?php if (isset($error)): ?>
            <div class="error">
                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" 
                       id="username" 
                       name="username" 
                       placeholder="Enter username" 
                       required 
                       autofocus
                       <?php echo $is_locked ? 'disabled' : ''; ?>>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" 
                       id="password" 
                       name="password" 
                       placeholder="Enter password" 
                       required
                       <?php echo $is_locked ? 'disabled' : ''; ?>>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me for 30 days</label>
            </div>
            
            <button type="submit" <?php echo $is_locked ? 'disabled' : ''; ?>>
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="loading" id="loading">
            <i class="fas fa-spinner"></i>
            <p>Authenticating...</p>
        </div>
        
        <div class="info">
            <strong><i class="fas fa-info-circle"></i> Credentials:</strong>
            Username: admin<br>
            Password: 1995
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Hausmeister Michael GmbH</p>
            <p>Secure Admin Panel v2.0</p>
        </div>
    </div>
    
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            document.getElementById('loading').style.display = 'block';
        });
    </script>
</body>
</html>