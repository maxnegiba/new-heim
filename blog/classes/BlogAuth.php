<?php
// blog/classes/BlogAuth.php
namespace Blog;

class BlogAuth extends BlogCore {
    private $maxLoginAttempts = 5;
    private $lockoutDuration = 900; // 15 minutes
    
    public function login($username, $password, $rememberMe = false) {
        try {
            // Check if user is locked out
            $stmt = $this->pdo->prepare("
                SELECT * FROM blog_admins 
                WHERE username = ? OR email = ?
            ");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$user) {
                $this->logFailedLogin($username);
                return ['success' => false, 'error' => 'Invalid credentials'];
            }
            
            // Check if account is locked
            if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
                return [
                    'success' => false, 
                    'error' => 'Account is locked. Please try again later.'
                ];
            }
            
            // Check if account is active
            if (!$user['is_active']) {
                return [
                    'success' => false, 
                    'error' => 'Account is disabled'
                ];
            }
            
            // Verify password
            if (!password_verify($password, $user['password_hash'])) {
                $this->incrementLoginAttempts($user['id']);
                return ['success' => false, 'error' => 'Invalid credentials'];
            }
            
            // Check if password needs rehashing
            if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->pdo->prepare("UPDATE blog_admins SET password_hash = ? WHERE id = ?");
                $stmt->execute([$newHash, $user['id']]);
            }
            
            // Successful login
            $this->resetLoginAttempts($user['id']);
            $this->updateLastLogin($user['id']);
            
            // Set session
            session_regenerate_id(true);
            $_SESSION['blog_admin'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'role' => $user['role'],
                'avatar' => $user['avatar']
            ];
            
            // Set remember me cookie if requested
            if ($rememberMe) {
                $token = bin2hex(random_bytes(32));
                $this->setRememberToken($user['id'], $token);
                setcookie('blog_remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
            }
            
            // Log activity
            $this->logActivity($user['id'], 'login', 'admin', $user['id']);
            
            return ['success' => true, 'user' => $_SESSION['blog_admin']];
            
        } catch (\Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return ['success' => false, 'error' => 'An error occurred'];
        }
    }
    
    public function logout() {
        if (isset($_SESSION['blog_admin'])) {
            $this->logActivity($_SESSION['blog_admin']['id'], 'logout', 'admin', $_SESSION['blog_admin']['id']);
        }
        
        // Clear session
        unset($_SESSION['blog_admin']);
        session_regenerate_id(true);
        
        // Clear remember token cookie
        if (isset($_COOKIE['blog_remember_token'])) {
            setcookie('blog_remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        return true;
    }
    
    public function checkAuth() {
        // Check session
        if (isset($_SESSION['blog_admin'])) {
            return $_SESSION['blog_admin'];
        }
        
        // Check remember token
        if (isset($_COOKIE['blog_remember_token'])) {
            return $this->loginWithRememberToken($_COOKIE['blog_remember_token']);
        }
        
        return false;
    }
    
    public function requireAuth($requiredRole = null) {
        $user = $this->checkAuth();
        
        if (!$user) {
            header('Location: /blog/admin');
            exit;
        }
        
        if ($requiredRole && !$this->hasRole($user['role'], $requiredRole)) {
            http_response_code(403);
            die('Access denied');
        }
        
        return $user;
    }
    
    public function hasRole($userRole, $requiredRole) {
        $roles = [
            'super_admin' => 4,
            'admin' => 3,
            'editor' => 2,
            'author' => 1
        ];
        
        return ($roles[$userRole] ?? 0) >= ($roles[$requiredRole] ?? 0);
    }
    
    public function createAdmin($data) {
        try {
            // Validate required fields
            if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
                throw new \Exception('Username, email and password are required');
            }
            
            // Check if username or email already exists
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM blog_admins 
                WHERE username = ? OR email = ?
            ");
            $stmt->execute([$data['username'], $data['email']]);
            
            if ($stmt->fetchColumn() > 0) {
                throw new \Exception('Username or email already exists');
            }
            
            // Hash password
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Insert admin
            $stmt = $this->pdo->prepare("
                INSERT INTO blog_admins 
                (username, email, password_hash, full_name, role, bio, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['username'],
                $data['email'],
                $passwordHash,
                $data['full_name'] ?? null,
                $data['role'] ?? 'author',
                $data['bio'] ?? null,
                $data['is_active'] ?? true
            ]);
            
            return $this->pdo->lastInsertId();
            
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function updatePassword($userId, $currentPassword, $newPassword) {
        $stmt = $this->pdo->prepare("SELECT password_hash FROM blog_admins WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            return ['success' => false, 'error' => 'Current password is incorrect'];
        }
        
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE blog_admins SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newHash, $userId]);
        
        $this->logActivity($userId, 'change_password', 'admin', $userId);
        
        return ['success' => true];
    }
    
    public function resetPassword($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM blog_admins WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'error' => 'Email not found'];
        }
        
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $this->pdo->prepare("
            UPDATE blog_admins 
            SET reset_token = ?, reset_token_expires = ? 
            WHERE id = ?
        ");
        $stmt->execute([$token, $expires, $user['id']]);
        
        // Send email (implement your email sending logic)
        $resetLink = "https://{$_SERVER['HTTP_HOST']}/blog/admin/reset-password?token={$token}";
        
        return ['success' => true, 'token' => $token];
    }
    
    protected function incrementLoginAttempts($userId) {
        $stmt = $this->pdo->prepare("
            UPDATE blog_admins 
            SET login_attempts = login_attempts + 1,
                locked_until = CASE 
                    WHEN login_attempts >= ? 
                    THEN DATE_ADD(NOW(), INTERVAL ? SECOND)
                    ELSE locked_until
                END
            WHERE id = ?
        ");
        $stmt->execute([$this->maxLoginAttempts - 1, $this->lockoutDuration, $userId]);
    }
    
    protected function resetLoginAttempts($userId) {
        $stmt = $this->pdo->prepare("
            UPDATE blog_admins 
            SET login_attempts = 0, locked_until = NULL 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
    }
    
    protected function updateLastLogin($userId) {
        $stmt = $this->pdo->prepare("
            UPDATE blog_admins 
            SET last_login = NOW() 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
    }
    
    protected function logFailedLogin($username) {
        // Log failed login attempt (you can expand this)
        error_log("Failed login attempt for: " . $username);
    }
    
    protected function setRememberToken($userId, $token) {
        $hashedToken = hash('sha256', $token);
        $stmt = $this->pdo->prepare("
            UPDATE blog_admins 
            SET remember_token = ? 
            WHERE id = ?
        ");
        $stmt->execute([$hashedToken, $userId]);
    }
    
    protected function loginWithRememberToken($token) {
        $hashedToken = hash('sha256', $token);
        
        $stmt = $this->pdo->prepare("
            SELECT * FROM blog_admins 
            WHERE remember_token = ? AND is_active = 1
        ");
        $stmt->execute([$hashedToken]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['blog_admin'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'role' => $user['role'],
                'avatar' => $user['avatar']
            ];
            
            $this->updateLastLogin($user['id']);
            
            return $_SESSION['blog_admin'];
        }
        
        return false;
    }
}