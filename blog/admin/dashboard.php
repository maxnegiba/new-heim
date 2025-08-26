<?php
session_start();
require_once __DIR__ . '/../../db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /blog/admin/");
    exit;
}

// Session timeout (30 minutes)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 1800)) {
    session_destroy();
    header("Location: /blog/admin/?timeout=1");
    exit;
}
$_SESSION['login_time'] = time();

$username = $_SESSION['admin_username'] ?? 'Admin';

// Get statistics
try {
    // Total posts
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM blog_posts");
    $totalPosts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Published posts
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM blog_posts WHERE status = 'published'");
    $publishedPosts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Draft posts
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM blog_posts WHERE status = 'draft'");
    $draftPosts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total views
    $stmt = $pdo->query("SELECT SUM(views) as total FROM blog_posts");
    $totalViews = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    
    // Recent posts
    $stmt = $pdo->query("
        SELECT id, title, status, views, created_at 
        FROM blog_posts 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $recentPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Popular posts
    $stmt = $pdo->query("
        SELECT id, title, views 
        FROM blog_posts 
        WHERE status = 'published' 
        ORDER BY views DESC 
        LIMIT 5
    ");
    $popularPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // If tables don't exist yet
    $totalPosts = 0;
    $publishedPosts = 0;
    $draftPosts = 0;
    $totalViews = 0;
    $recentPosts = [];
    $popularPosts = [];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Blog Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .header-nav {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .header-nav a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .header-nav a:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
        }
        
        .logout-btn:hover {
            background: white;
            color: #667eea;
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        /* Welcome Section */
        .welcome-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border-left: 5px solid #667eea;
        }
        
        .welcome-section h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .welcome-section p {
            color: #666;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .stat-card.total .stat-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .stat-card.published .stat-icon {
            background: linear-gradient(135deg, #00c896, #00d68f);
            color: white;
        }
        
        .stat-card.draft .stat-icon {
            background: linear-gradient(135deg, #ffa726, #fb8c00);
            color: white;
        }
        
        .stat-card.views .stat-icon {
            background: linear-gradient(135deg, #26c6da, #00acc1);
            color: white;
        }
        
        .stat-card h3 {
            font-size: 32px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Content Sections */
        .content-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f2f5;
        }
        
        .section-header h3 {
            color: #333;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-header a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .section-header a:hover {
            color: #764ba2;
        }
        
        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            text-align: left;
            padding: 12px;
            background: #f8f9fa;
            color: #666;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #f0f2f5;
        }
        
        .data-table tr:hover {
            background: #f8f9fa;
        }
        
        .post-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .post-meta {
            font-size: 12px;
            color: #999;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-badge.published {
            background: #d4f4dd;
            color: #00c896;
        }
        
        .status-badge.draft {
            background: #fff4e5;
            color: #ffa726;
        }
        
        .status-badge.scheduled {
            background: #e3f2fd;
            color: #42a5f5;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .quick-action {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .quick-action:hover {
            background: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }
        
        .quick-action i {
            font-size: 30px;
            color: #667eea;
            margin-bottom: 10px;
            display: block;
        }
        
        .quick-action span {
            font-weight: 600;
            font-size: 14px;
        }
        
        /* Popular Posts List */
        .popular-list {
            list-style: none;
        }
        
        .popular-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f0f2f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .popular-list li:last-child {
            border-bottom: none;
        }
        
        .popular-title {
            flex: 1;
            margin-right: 10px;
            color: #333;
            font-size: 14px;
        }
        
        .popular-views {
            background: #f0f2f5;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 12px;
            color: #666;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ddd;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>
                <i class="fas fa-tachometer-alt"></i>
                Blog Dashboard
            </h1>
            <nav class="header-nav">
                <a href="/blog" target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Blog
                </a>
                <a href="/blog/admin/posts.php">
                    <i class="fas fa-file-alt"></i> Posts
                </a>
                <a href="/blog/admin/new-post.php">
                    <i class="fas fa-plus"></i> New Post
                </a>
                <a href="/blog/admin/?logout=1" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2>Welcome back, <?php echo htmlspecialchars($username); ?>! 👋</h2>
            <p>Here's what's happening with your blog today.</p>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3><?php echo number_format($totalPosts); ?></h3>
                <p>Total Posts</p>
            </div>
            
            <div class="stat-card published">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3><?php echo number_format($publishedPosts); ?></h3>
                <p>Published</p>
            </div>
            
            <div class="stat-card draft">
                <div class="stat-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3><?php echo number_format($draftPosts); ?></h3>
                <p>Drafts</p>
            </div>
            
            <div class="stat-card views">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3><?php echo number_format($totalViews); ?></h3>
                <p>Total Views</p>
            </div>
        </div>
        
        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Recent Posts -->
            <div class="content-section">
                <div class="section-header">
                    <h3>
                        <i class="fas fa-clock"></i>
                        Recent Posts
                    </h3>
                    <a href="/blog/admin/posts.php">View All →</a>
                </div>
                
                <?php if (!empty($recentPosts)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentPosts as $post): ?>
                                <tr>
                                    <td>
                                        <div class="post-title">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </div>
                                        <div class="post-meta">
                                            <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $post['status']; ?>">
                                            <?php echo $post['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo number_format($post['views']); ?></td>
                                    <td>
                                        <a href="/blog/admin/edit-post.php?id=<?php echo $post['id']; ?>" 
                                           style="color: #667eea; text-decoration: none;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No posts yet. Create your first post!</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div>
                <!-- Quick Actions -->
                <div class="content-section" style="margin-bottom: 30px;">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-bolt"></i>
                            Quick Actions
                        </h3>
                    </div>
                    
                    <div class="quick-actions">
                        <a href="/blog/admin/new-post.php" class="quick-action">
                            <i class="fas fa-plus-circle"></i>
                            <span>New Post</span>
                        </a>
                        <a href="/blog/admin/posts.php" class="quick-action">
                            <i class="fas fa-list"></i>
                            <span>All Posts</span>
                        </a>
                        <a href="/blog/admin/categories.php" class="quick-action">
                            <i class="fas fa-folder"></i>
                            <span>Categories</span>
                        </a>
                        <a href="/blog/admin/settings.php" class="quick-action">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                </div>
                
                <!-- Popular Posts -->
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-fire"></i>
                            Popular Posts
                        </h3>
                    </div>
                    
                    <?php if (!empty($popularPosts)): ?>
                        <ul class="popular-list">
                            <?php foreach ($popularPosts as $post): ?>
                                <li>
                                    <span class="popular-title">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </span>
                                    <span class="popular-views">
                                        <i class="fas fa-eye"></i> <?php echo number_format($post['views']); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-chart-line"></i>
                            <p>No data yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>