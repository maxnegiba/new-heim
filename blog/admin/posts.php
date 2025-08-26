<?php
// blog/admin/posts.php
session_start();
require_once __DIR__ . '/../../db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /blog/admin/");
    exit;
}

// Handle delete action
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Post deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting post: " . $e->getMessage();
    }
}

// Get filter parameters
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where = [];
$params = [];

if ($status_filter) {
    $where[] = "status = ?";
    $params[] = $status_filter;
}

if ($search) {
    $where[] = "(title LIKE ? OR body LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereClause = $where ? "WHERE " . implode(" AND ", $where) : "";

// Get posts
try {
    $sql = "SELECT * FROM blog_posts $whereClause ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $posts = [];
    $error = "Error loading posts: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts - Blog Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }
        
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
        }
        
        .header-nav {
            display: flex;
            gap: 20px;
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
        
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filters select, .filters input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filters button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .posts-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #f0f2f5;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .post-title {
            font-weight: 600;
            color: #333;
            display: block;
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
        
        .actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-icon {
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .btn-edit {
            background: #667eea;
            color: white;
        }
        
        .btn-edit:hover {
            background: #5a67d8;
        }
        
        .btn-delete {
            background: #f56565;
            color: white;
        }
        
        .btn-delete:hover {
            background: #e53e3e;
        }
        
        .btn-view {
            background: #48bb78;
            color: white;
        }
        
        .btn-view:hover {
            background: #38a169;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4f4dd;
            color: #00c896;
            border-left: 4px solid #00c896;
        }
        
        .alert-error {
            background: #fee;
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px;
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
                <i class="fas fa-list"></i> All Posts
            </h1>
            <nav class="header-nav">
                <a href="/blog/admin/dashboard.php">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                <a href="/blog/admin/new-post.php">
                    <i class="fas fa-plus"></i> New Post
                </a>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <?php if (isset($message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="filters">
            <form method="GET" action="" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center; width: 100%;">
                <select name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="published" <?php echo $status_filter === 'published' ? 'selected' : ''; ?>>Published</option>
                    <option value="draft" <?php echo $status_filter === 'draft' ? 'selected' : ''; ?>>Draft</option>
                </select>
                
                <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
                
                <button type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
                
                <?php if ($status_filter || $search): ?>
                    <a href="/blog/admin/posts.php" style="color: #666; text-decoration: none;">
                        <i class="fas fa-times"></i> Clear filters
                    </a>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="posts-table">
            <?php if (empty($posts)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No posts found</h3>
                    <p>Try adjusting your filters or <a href="/blog/admin/new-post.php">create a new post</a>.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="35%">Title</th>
                            <th width="15%">Status</th>
                            <th width="10%">Views</th>
                            <th width="15%">Created</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?php echo $post['id']; ?></td>
                                <td>
                                    <span class="post-title"><?php echo htmlspecialchars($post['title']); ?></span>
                                    <span class="post-meta">
                                        <?php if (!empty($post['slug'])): ?>
                                            Slug: <?php echo htmlspecialchars($post['slug']); ?>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $post['status'] ?? 'draft'; ?>">
                                        <?php echo $post['status'] ?? 'draft'; ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($post['views'] ?? 0); ?></td>
                                <td><?php echo date('d.m.Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="/blog/admin/edit-post.php?id=<?php echo $post['id']; ?>" class="btn-icon btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        
                                        <?php if (($post['status'] ?? '') === 'published' && !empty($post['slug'])): ?>
                                            <a href="/blog/<?php echo $post['slug']; ?>" target="_blank" class="btn-icon btn-view">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="/blog/admin/posts.php?delete=1&id=<?php echo $post['id']; ?>" 
                                           class="btn-icon btn-delete"
                                           onclick="return confirm('Are you sure you want to delete this post?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>