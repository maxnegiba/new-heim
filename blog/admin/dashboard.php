<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: /blog/admin");
    exit;
}

require_once __DIR__ . '/../../db.php';

// Procesare salvare postare
if ($_POST['save'] ?? false) {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    
    if (empty($title) || empty($body)) {
        $error = 'Titel und Inhalt sind erforderlich';
    } else {
        // Generare slug
        $slug = strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $title));
        $slug = trim($slug, '-');
        
        // Verificare dacă slug-ul există deja
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() > 0) {
            $slug .= '-' . time();
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, body) VALUES (?,?,?)");
            $stmt->execute([$title, $slug, $body]);
            $success = 'Beitrag erfolgreich gespeichert!';
        } catch (PDOException $e) {
            $error = 'Fehler beim Speichern: ' . $e->getMessage();
        }
    }
}

$page_title = 'Admin Dashboard | Blog';
include '../includes/blog-header.php';
?>

<style>
.admin-dashboard {
    max-width: 1200px;
    margin: 120px auto 60px;
    padding: 0 20px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.dashboard-header h1 {
    color: #2c3e50;
}

.logout-btn {
    background: #7f8c8d;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.logout-btn:hover {
    background: #6c7a7d;
}

.admin-form {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.admin-form h2 {
    color: #2c3e50;
    margin-bottom: 20px;
}

.admin-input, .admin-textarea {
    width: 100%;
    padding: 15px;
    margin: 15px 0;
    border: 2px solid #eee;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
}

.admin-input:focus, .admin-textarea:focus {
    border-color: #d32f2f;
    outline: none;
}

.admin-textarea {
    min-height: 200px;
    resize: vertical;
}

.admin-submit-btn {
    background: #d32f2f;
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.admin-submit-btn:hover {
    background: #b71c1c;
}

.message {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.success-message {
    background: #e8f5e9;
    color: #2e7d32;
    border-left: 4px solid #2e7d32;
}

.error-message {
    background: #ffebee;
    color: #c62828;
    border-left: 4px solid #c62828;
}

@media (max-width: 768px) {
    .admin-dashboard {
        margin: 100px auto 40px;
    }
    
    .dashboard-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .admin-form {
        padding: 20px;
    }
}
</style>

<div class="admin-dashboard">
    <div class="dashboard-header">
        <h1>Blog Admin Dashboard</h1>
        <a href="/blog/admin?logout=1" class="logout-btn">Abmelden</a>
    </div>
    
    <?php if (isset($success)): ?>
        <div class="message success-message">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="message error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <div class="admin-form">
        <h2>Neuen Blogbeitrag erstellen</h2>
        <form method="post">
            <input type="text" name="title" placeholder="Beitragstitel" class="admin-input" 
                   value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
            <textarea name="body" placeholder="Beitragsinhalt..." class="admin-textarea" required><?php 
                echo htmlspecialchars($_POST['body'] ?? ''); 
            ?></textarea>
            <button type="submit" name="save" class="admin-submit-btn">Beitrag speichern</button>
        </form>
    </div>
</div>

<?php include(__DIR__ . '/../../../includes/footer.php'); ?>