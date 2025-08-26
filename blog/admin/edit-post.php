<?php
// blog/admin/edit-post.php
session_start();
require_once __DIR__ . '/../../db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /blog/admin/");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header("Location: /blog/admin/posts.php");
    exit;
}

// Get post data
try {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post) {
        header("Location: /blog/admin/posts.php");
        exit;
    }
} catch (PDOException $e) {
    $error = "Error loading post: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    $featured_image = trim($_POST['featured_image'] ?? '');
    $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $meta_keywords = trim($_POST['meta_keywords'] ?? '');
    
    if (empty($title) || empty($body)) {
        $error = 'Title and content are required!';
    } else {
        // Generate slug if changed
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
        $slug = trim($slug, '-');
        
        // Check if slug exists (excluding current post)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $id]);
        if ($stmt->fetchColumn() > 0) {
            $slug .= '-' . time();
        }
        
        // Calculate reading time
        $word_count = str_word_count(strip_tags($body));
        $reading_time = max(1, ceil($word_count / 200));
        
        // Auto-generate excerpt if empty
        if (empty($excerpt)) {
            $excerpt = substr(strip_tags($body), 0, 200);
            if (strlen(strip_tags($body)) > 200) {
                $excerpt .= '...';
            }
        }
        
        // Use post title for meta_title if empty
        if (empty($meta_title)) {
            $meta_title = $title;
        }
        
        // Use excerpt for meta_description if empty
        if (empty($meta_description)) {
            $meta_description = $excerpt;
        }
        
        try {
            // Check which columns exist
            $tableColumns = $pdo->query("SHOW COLUMNS FROM blog_posts")->fetchAll(PDO::FETCH_COLUMN);
            
            // Build dynamic UPDATE query
            $updates = ['title = ?', 'body = ?'];
            $params = [$title, $body];
            
            // Add optional fields if columns exist
            $optionalFields = [
                'slug' => $slug,
                'excerpt' => $excerpt,
                'status' => $status,
                'category_id' => $category_id,
                'reading_time' => $reading_time,
                'meta_title' => $meta_title,
                'meta_description' => $meta_description,
                'meta_keywords' => !empty($meta_keywords) ? $meta_keywords : null,
                'featured_image' => !empty($featured_image) ? $featured_image : null,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            foreach ($optionalFields as $field => $value) {
                if (in_array($field, $tableColumns)) {
                    $updates[] = "$field = ?";
                    $params[] = $value;
                }
            }
            
            // Add ID at the end
            $params[] = $id;
            
            // Execute update
            $sql = "UPDATE blog_posts SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $message = 'Post updated successfully!';
            
            // Reload post data
            $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
            $stmt->execute([$id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            $error = 'Error updating post: ' . $e->getMessage();
        }
    }
}

// Get categories (if table exists)
$categories = [];
try {
    $tableExists = $pdo->query("SHOW TABLES LIKE 'blog_categories'")->rowCount() > 0;
    if ($tableExists) {
        $stmt = $pdo->query("SELECT id, name FROM blog_categories ORDER BY name");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // Categories table doesn't exist, that's OK
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Blog Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Copy the same styles from new-post.php */
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
            max-width: 1200px;
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
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .editor-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        @media (max-width: 1024px) {
            .editor-container {
                grid-template-columns: 1fr;
            }
        }
        
        .editor-main, .editor-sidebar {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        textarea {
            min-height: 400px;
            resize: vertical;
            font-family: inherit;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
            margin-left: 10px;
        }
        
        .btn-secondary:hover {
            background: #d0d0d0;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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
        
        .section-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f2f5;
        }
        
        .toolbar {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            flex-wrap: wrap;
        }
        
        .toolbar button {
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .toolbar button:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .featured-image-preview {
            margin-top: 10px;
            max-width: 200px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .featured-image-preview img {
            width: 100%;
            height: auto;
        }
        
        .help-text {
            font-size: 13px;
            color: #999;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1>
                <i class="fas fa-edit"></i> Edit Post
            </h1>
            <nav class="header-nav">
                <a href="/blog/admin/dashboard.php">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
                <a href="/blog/admin/posts.php">
                    <i class="fas fa-list"></i> All Posts
                </a>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="editor-container">
                <!-- Main Editor -->
                <div class="editor-main">
                    <div class="form-group">
                        <label for="title">Post Title *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               placeholder="Enter post title..." 
                               required
                               value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="body">Content *</label>
                        <div class="toolbar">
                            <button type="button" onclick="insertTag('**', '**')" title="Bold">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="insertTag('*', '*')" title="Italic">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="insertTag('\n## ', '\n')" title="Heading 2">
                                H2
                            </button>
                            <button type="button" onclick="insertTag('\n### ', '\n')" title="Heading 3">
                                H3
                            </button>
                            <button type="button" onclick="insertTag('\n- ', '')" title="List">
                                <i class="fas fa-list"></i>
                            </button>
                            <button type="button" onclick="insertTag('[', '](url)')" title="Link">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" onclick="insertTag('\n> ', '\n')" title="Quote">
                                <i class="fas fa-quote-left"></i>
                            </button>
                        </div>
                        <textarea id="body" 
                                  name="body" 
                                  placeholder="Write your post content here..." 
                                  required><?php echo htmlspecialchars($post['body'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="excerpt">Excerpt</label>
                        <textarea id="excerpt" 
                                  name="excerpt" 
                                  rows="3"
                                  placeholder="Brief description..."><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
                        <div class="help-text">A short summary of your post that appears in post listings.</div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="editor-sidebar">
                    <h3 class="section-title">Publish Settings</h3>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="draft" <?php echo ($post['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="scheduled" <?php echo ($post['status'] ?? '') === 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                        </select>
                    </div>
                    
                    <?php if (!empty($categories)): ?>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id">
                            <option value="">— No Category —</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo ($post['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Featured Image</label>
                        <input type="text" 
                               id="featured_image" 
                               name="featured_image" 
                               placeholder="Image URL..."
                               value="<?php echo htmlspecialchars($post['featured_image'] ?? ''); ?>">
                        <?php if (!empty($post['featured_image'])): ?>
                            <div class="featured-image-preview">
                                <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="Featured Image">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h3 class="section-title">SEO Settings</h3>
                    
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" 
                               id="meta_title" 
                               name="meta_title" 
                               placeholder="SEO title..."
                               value="<?php echo htmlspecialchars($post['meta_title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  rows="3"
                                  placeholder="SEO description..."><?php echo htmlspecialchars($post['meta_description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_keywords">Meta Keywords</label>
                        <input type="text" 
                               id="meta_keywords" 
                               name="meta_keywords" 
                               placeholder="keyword1, keyword2..."
                               value="<?php echo htmlspecialchars($post['meta_keywords'] ?? ''); ?>">
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Post
                        </button>
                        <a href="/blog/admin/posts.php" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                    
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #f0f2f5;">
                        <p class="help-text">
                            <i class="fas fa-info-circle"></i> 
                            Created: <?php echo date('d.m.Y H:i', strtotime($post['created_at'])); ?>
                            <?php if (!empty($post['updated_at'])): ?>
                                <br>Updated: <?php echo date('d.m.Y H:i', strtotime($post['updated_at'])); ?>
                            <?php endif; ?>
                            <br>Views: <?php echo number_format($post['views'] ?? 0); ?>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        function insertTag(openTag, closeTag) {
            const textarea = document.getElementById('body');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            const replacement = openTag + selectedText + closeTag;
            
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            
            const newCursorPos = start + openTag.length + selectedText.length;
            textarea.focus();
            textarea.setSelectionRange(newCursorPos, newCursorPos);
        }
    </script>
</body>
</html>