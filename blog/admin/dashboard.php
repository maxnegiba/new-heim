<?php
session_start();

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /blog/admin/");
    exit;
}

$username = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            color: #333;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .welcome-box h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .card h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .card a:hover {
            background: #5a67d8;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Admin Dashboard</h1>
        <a href="/blog/admin/?logout=1" class="logout-btn">Logout</a>
    </div>
    
    <div class="container">
        <div class="welcome-box">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>! 👋</h2>
            <p>You are successfully logged into the admin panel.</p>
        </div>
        
        <div class="success">
            ✅ Login system is working correctly!
        </div>
        
        <div class="cards">
            <div class="card">
                <h3>📝 Blog Posts</h3>
                <p>Manage your blog posts</p>
                <a href="/blog/admin/posts.php">View Posts</a>
            </div>
            
            <div class="card">
                <h3>➕ New Post</h3>
                <p>Create a new blog post</p>
                <a href="/blog/admin/new-post.php">Create Post</a>
            </div>
            
            <div class="card">
                <h3>💬 Comments</h3>
                <p>Moderate comments</p>
                <a href="/blog/admin/comments.php">View Comments</a>
            </div>
            
            <div class="card">
                <h3>⚙️ Settings</h3>
                <p>Configure blog settings</p>
                <a href="/blog/admin/settings.php">Settings</a>
            </div>
        </div>
    </div>
</body>
</html>