<?php
session_start();
require_once __DIR__ . '/../../db.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /blog/admin/");
    exit;
}

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $status = $_POST['status'] ?? 'draft';
    
    // Handle category_id - convert empty string to NULL
    $category_id = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
    
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $meta_keywords = trim($_POST['meta_keywords'] ?? '');
    
    if (empty($title) || empty($body)) {
        $error = 'Title and content are required!';
    } else {
        // Generate slug
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
        $slug = trim($slug, '-');
        
        // Check if slug exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = ?");
        $stmt->execute([$slug]);
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
            // Check which columns exist in your table
            $tableColumns = $pdo->query("SHOW COLUMNS FROM blog_posts")->fetchAll(PDO::FETCH_COLUMN);
            
            // Build dynamic INSERT query based on existing columns
            $columns = ['title', 'slug', 'body'];
            $values = [$title, $slug, $body];
            $placeholders = ['?', '?', '?'];
            
            // Add optional columns if they exist
            if (in_array('excerpt', $tableColumns)) {
                $columns[] = 'excerpt';
                $values[] = $excerpt;
                $placeholders[] = '?';
            }
            
            if (in_array('status', $tableColumns)) {
                $columns[] = 'status';
                $values[] = $status;
                $placeholders[] = '?';
            }
            
            if (in_array('category_id', $tableColumns)) {
                $columns[] = 'category_id';
                $values[] = $category_id; // This will be NULL if empty
                $placeholders[] = '?';
            }
            
            if (in_array('reading_time', $tableColumns)) {
                $columns[] = 'reading_time';
                $values[] = $reading_time;
                $placeholders[] = '?';
            }
            
            if (in_array('meta_title', $tableColumns)) {
                $columns[] = 'meta_title';
                $values[] = $meta_title;
                $placeholders[] = '?';
            }
            
            if (in_array('meta_description', $tableColumns)) {
                $columns[] = 'meta_description';
                $values[] = $meta_description;
                $placeholders[] = '?';
            }
            
            if (in_array('meta_keywords', $tableColumns)) {
                $columns[] = 'meta_keywords';
                $values[] = !empty($meta_keywords) ? $meta_keywords : null;
                $placeholders[] = '?';
            }
            
            if (in_array('author', $tableColumns)) {
                $columns[] = 'author';
                $values[] = $_SESSION['admin_username'];
                $placeholders[] = '?';
            }
            
            if (in_array('views', $tableColumns)) {
                $columns[] = 'views';
                $values[] = 0;
                $placeholders[] = '?';
            }
            
            // Build and execute the query
            $sql = "INSERT INTO blog_posts (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
            
            $post_id = $pdo->lastInsertId();
            $message = 'Post created successfully!';
            
            // Clear form
            $_POST = [];
            
            // Optional: Redirect to posts list
            // header("Location: /blog/admin/posts.php?success=1");
            // exit;
            
        } catch (PDOException $e) {
            $error = 'Error creating post: ' . $e->getMessage();
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
    <title>New Post - Blog Admin</title>
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
        
        .char-count {
            text-align: right;
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        
        .toolbar {
            display: flex;
            gap: 10px;
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
                <i class="fas fa-plus-circle"></i> New Post
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
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo htmlspecialchars($message); ?> 
                <a href="/blog/admin/posts.php" style="color: inherit; margin-left: 10px;">View all posts →</a></span>
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
                               value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
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
                                <i class="fas fa-heading"></i> H2
                            </button>
                            <button type="button" onclick="insertTag('\n### ', '\n')" title="Heading 3">
                                <i class="fas fa-heading"></i> H3
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
                            <button type="button" onclick="insertTag('`', '`')" title="Code">
                                <i class="fas fa-code"></i>
                            </button>
                        </div>
                        <textarea id="body" 
                                  name="body" 
                                  placeholder="Write your post content here..." 
                                  required><?php echo htmlspecialchars($_POST['body'] ?? ''); ?></textarea>
                        <div class="char-count">
                            <span id="wordCount">0</span> words | <span id="charCount">0</span> characters
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="excerpt">Excerpt</label>
                        <textarea id="excerpt" 
                                  name="excerpt" 
                                  rows="3"
                                  placeholder="Brief description (auto-generated if left empty)..."><?php echo htmlspecialchars($_POST['excerpt'] ?? ''); ?></textarea>
                        <div class="help-text">A short summary of your post that appears in post listings.</div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="editor-sidebar">
                    <h3 class="section-title">Publish Settings</h3>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="draft" <?php echo ($_POST['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo ($_POST['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="scheduled" <?php echo ($_POST['status'] ?? '') === 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                        </select>
                    </div>
                    
                    <?php if (!empty($categories)): ?>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id">
                            <option value="">— No Category —</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo ($_POST['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <h3 class="section-title">SEO Settings</h3>
                    
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" 
                               id="meta_title" 
                               name="meta_title" 
                               placeholder="SEO title (uses post title if empty)"
                               value="<?php echo htmlspecialchars($_POST['meta_title'] ?? ''); ?>">
                        <div class="help-text">Title that appears in search results (50-60 characters).</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea id="meta_description" 
                                  name="meta_description" 
                                  rows="3"
                                  placeholder="SEO description..."><?php echo htmlspecialchars($_POST['meta_description'] ?? ''); ?></textarea>
                        <div class="help-text">Description for search results (150-160 characters).</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_keywords">Meta Keywords</label>
                        <input type="text" 
                               id="meta_keywords" 
                               name="meta_keywords" 
                               placeholder="keyword1, keyword2, keyword3"
                               value="<?php echo htmlspecialchars($_POST['meta_keywords'] ?? ''); ?>">
                        <div class="help-text">Comma-separated keywords for SEO.</div>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Post
                        </button>
                        <a href="/blog/admin/dashboard.php" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        // Word and character counter
        const bodyTextarea = document.getElementById('body');
        const wordCount = document.getElementById('wordCount');
        const charCount = document.getElementById('charCount');
        
        function updateCounts() {
            const text = bodyTextarea.value;
            const words = text.trim().split(/\s+/).filter(word => word.length > 0);
            wordCount.textContent = words.length;
            charCount.textContent = text.length;
        }
        
        bodyTextarea.addEventListener('input', updateCounts);
        updateCounts();
        
        // Insert formatting tags
        function insertTag(openTag, closeTag) {
            const textarea = document.getElementById('body');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            const replacement = openTag + selectedText + closeTag;
            
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            
            // Set cursor position
            const newCursorPos = start + openTag.length + selectedText.length;
            textarea.focus();
            textarea.setSelectionRange(newCursorPos, newCursorPos);
            
            updateCounts();
        }
        
        // Auto-save draft to localStorage
        let autoSaveTimer;
        function autoSave() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                localStorage.setItem('draft_title', document.getElementById('title').value);
                localStorage.setItem('draft_body', bodyTextarea.value);
                localStorage.setItem('draft_excerpt', document.getElementById('excerpt').value);
                console.log('Draft saved locally at ' + new Date().toLocaleTimeString());
            }, 2000);
        }
        
        document.getElementById('title').addEventListener('input', autoSave);
        bodyTextarea.addEventListener('input', autoSave);
        document.getElementById('excerpt').addEventListener('input', autoSave);
        
        // Restore draft on load
        window.addEventListener('load', function() {
            <?php if (empty($_POST) && empty($message)): ?>
            const draftTitle = localStorage.getItem('draft_title');
            const draftBody = localStorage.getItem('draft_body');
            const draftExcerpt = localStorage.getItem('draft_excerpt');
            
            if (draftTitle || draftBody) {
                if (confirm('Restore previous draft?')) {
                    if (draftTitle) document.getElementById('title').value = draftTitle;
                    if (draftBody) bodyTextarea.value = draftBody;
                    if (draftExcerpt) document.getElementById('excerpt').value = draftExcerpt;
                    updateCounts();
                }
            }
            <?php endif; ?>
        });
        
        // Clear draft on successful save
        <?php if ($message): ?>
        localStorage.removeItem('draft_title');
        localStorage.removeItem('draft_body');
        localStorage.removeItem('draft_excerpt');
        <?php endif; ?>
    </script>
</body>
</html>