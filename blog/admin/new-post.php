<?php
// blog/admin/new-post.php
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
    $featured_image = trim($_POST['featured_image'] ?? '');
    
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
            $optionalFields = [
                'excerpt' => $excerpt,
                'status' => $status,
                'category_id' => $category_id,
                'reading_time' => $reading_time,
                'meta_title' => $meta_title,
                'meta_description' => $meta_description,
                'meta_keywords' => !empty($meta_keywords) ? $meta_keywords : null,
                'author' => $_SESSION['admin_username'],
                'views' => 0,
                'featured_image' => !empty($featured_image) ? $featured_image : null
            ];
            
            foreach ($optionalFields as $field => $value) {
                if (in_array($field, $tableColumns)) {
                    $columns[] = $field;
                    $values[] = $value;
                    $placeholders[] = '?';
                }
            }
            
            // Build and execute the query
            $sql = "INSERT INTO blog_posts (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
            
            $post_id = $pdo->lastInsertId();
            $message = 'Post created successfully!';
            
            // Clear form
            $_POST = [];
            
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
        
        /* Media Library Styles */
        .media-library-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            z-index: 10000;
            overflow: auto;
        }
        
        .media-library-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .media-library-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }
        
        .media-library-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .media-library-body {
            flex: 1;
            overflow: auto;
            padding: 20px;
        }
        
        .media-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .media-tab {
            padding: 10px 20px;
            background: #f0f2f5;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .media-tab.active {
            background: #667eea;
            color: white;
        }
        
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .upload-area:hover,
        .upload-area.drag-over {
            border-color: #667eea;
            background: #f8f9ff;
        }
        
        .upload-area i {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .media-item {
            position: relative;
            border: 2px solid transparent;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
            background: #f0f2f5;
            aspect-ratio: 1;
        }
        
        .media-item:hover {
            border-color: #667eea;
            transform: scale(1.05);
        }
        
        .media-item.selected {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
        }
        
        .media-item img,
        .media-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .media-item-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
            padding: 10px;
            font-size: 12px;
        }
        
        .media-item-type {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 11px;
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
        
        .remove-featured {
            margin-top: 10px;
            color: #e74c3c;
            cursor: pointer;
            font-size: 14px;
        }
        
        .upload-progress {
            display: none;
            margin-top: 20px;
        }
        
        .upload-progress.active {
            display: block;
        }
        
        .progress-bar {
            height: 30px;
            background: #f0f2f5;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }
        
        .char-count {
            text-align: right;
            font-size: 12px;
            color: #999;
            margin-top: 5px;
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
                            <button type="button" onclick="openMediaLibrary('content')" title="Add Media">
                                <i class="fas fa-image"></i> Media
                            </button>
                            <button type="button" onclick="insertTag('\n```\n', '\n```\n')" title="Code Block">
                                <i class="fas fa-code"></i>
                            </button>
                            <button type="button" onclick="insertTag('---\n', '')" title="Horizontal Line">
                                —
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
                    
                    <div class="form-group">
                        <label>Featured Image</label>
                        <button type="button" class="btn btn-secondary" onclick="openMediaLibrary('featured')" style="margin-left: 0;">
                            <i class="fas fa-image"></i> Select Image
                        </button>
                        <input type="hidden" id="featured_image" name="featured_image" value="<?php echo htmlspecialchars($_POST['featured_image'] ?? ''); ?>">
                        <div id="featured_image_preview" class="featured-image-preview" style="<?php echo empty($_POST['featured_image']) ? 'display:none;' : ''; ?>">
                            <?php if (!empty($_POST['featured_image'])): ?>
                                <img src="<?php echo htmlspecialchars($_POST['featured_image']); ?>" alt="Featured Image">
                            <?php endif; ?>
                        </div>
                        <div class="remove-featured" id="remove_featured" style="<?php echo empty($_POST['featured_image']) ? 'display:none;' : ''; ?>" onclick="removeFeaturedImage()">
                            <i class="fas fa-times"></i> Remove Image
                        </div>
                    </div>
                    
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
    
    <!-- Media Library Modal -->
    <div id="mediaLibraryModal" class="media-library-modal">
        <div class="media-library-content">
            <div class="media-library-header">
                <h2>Media Library</h2>
                <button type="button" onclick="closeMediaLibrary()" style="background: none; border: none; font-size: 24px; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="media-library-body">
                <div class="media-tabs">
                    <button class="media-tab active" onclick="switchMediaTab('upload')">Upload</button>
                    <button class="media-tab" onclick="switchMediaTab('library')">Media Library</button>
                </div>
                
                <div id="uploadTab">
                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h3>Drop files here or click to upload</h3>
                        <p>Maximum file size: 10MB</p>
                        <p>Supported formats: JPG, PNG, GIF, WebP, MP4, WebM</p>
                        <input type="file" id="fileInput" style="display: none;" accept="image/*,video/*" multiple>
                    </div>
                    
                    <div class="upload-progress" id="uploadProgress">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill" style="width: 0%">0%</div>
                        </div>
                    </div>
                </div>
                
                <div id="libraryTab" style="display: none;">
                    <div class="media-grid" id="mediaGrid">
                        <!-- Media items will be loaded here -->
                    </div>
                </div>
                
                <div style="text-align: right; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                    <button type="button" class="btn btn-secondary" onclick="closeMediaLibrary()">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="insertSelectedMedia()">Insert Selected</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Global variables
        let currentMediaTarget = null;
        let selectedMedia = [];
        
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
        
        // Media Library Functions
        function openMediaLibrary(target) {
            currentMediaTarget = target;
            selectedMedia = [];
            document.getElementById('mediaLibraryModal').classList.add('active');
            loadMediaLibrary();
        }
        
        function closeMediaLibrary() {
            document.getElementById('mediaLibraryModal').classList.remove('active');
            currentMediaTarget = null;
            selectedMedia = [];
        }
        
        function switchMediaTab(tab) {
            document.querySelectorAll('.media-tab').forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');
            
            if (tab === 'upload') {
                document.getElementById('uploadTab').style.display = 'block';
                document.getElementById('libraryTab').style.display = 'none';
            } else {
                document.getElementById('uploadTab').style.display = 'none';
                document.getElementById('libraryTab').style.display = 'block';
                loadMediaLibrary();
            }
        }
        
        function loadMediaLibrary() {
            fetch('/blog/admin/upload.php?action=list')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayMediaItems(data.media);
                    }
                })
                .catch(error => {
                    console.error('Error loading media:', error);
                    // If fetch fails, show empty state
                    document.getElementById('mediaGrid').innerHTML = '<p>No media files found. Upload some files first!</p>';
                });
        }
        
        function displayMediaItems(media) {
            const grid = document.getElementById('mediaGrid');
            
            if (media.length === 0) {
                grid.innerHTML = '<p>No media files found. Upload some files first!</p>';
                return;
            }
            
            grid.innerHTML = media.map(item => {
                const isVideo = item.type === 'video';
                const thumbnail = item.thumbnail || item.url;
                
                return `
                    <div class="media-item" onclick="selectMedia('${item.url}', '${item.type}', this)">
                        ${isVideo ? 
                            `<video src="${item.url}" muted></video>
                             <div class="media-item-type">VIDEO</div>` : 
                            `<img src="${thumbnail}" alt="${item.name}">`
                        }
                        <div class="media-item-info">
                            ${item.name ? item.name.substring(0, 20) : 'Media'}
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        function selectMedia(url, type, element) {
            if (currentMediaTarget === 'featured') {
                // For featured image, only allow single selection
                document.querySelectorAll('.media-item').forEach(item => {
                    item.classList.remove('selected');
                });
                element.classList.add('selected');
                selectedMedia = [{url: url, type: type}];
            } else {
                // For content, allow multiple selection
                element.classList.toggle('selected');
                const index = selectedMedia.findIndex(m => m.url === url);
                
                if (index > -1) {
                    selectedMedia.splice(index, 1);
                } else {
                    selectedMedia.push({url: url, type: type});
                }
            }
        }
        
        function insertSelectedMedia() {
            if (selectedMedia.length === 0) {
                alert('Please select at least one media item');
                return;
            }
            
            if (currentMediaTarget === 'featured') {
                // Set featured image
                const media = selectedMedia[0];
                document.getElementById('featured_image').value = media.url;
                document.getElementById('featured_image_preview').innerHTML = `<img src="${media.url}" alt="Featured Image">`;
                document.getElementById('featured_image_preview').style.display = 'block';
                document.getElementById('remove_featured').style.display = 'block';
            } else {
                // Insert into content
                const textarea = document.getElementById('body');
                const cursorPos = textarea.selectionStart;
                
                let mediaMarkup = '\n\n';
                selectedMedia.forEach(media => {
                    if (media.type === 'video') {
                        mediaMarkup += `<video controls width="100%">\n  <source src="${media.url}" type="video/mp4">\n  Your browser does not support the video tag.\n</video>\n\n`;
                    } else {
                        mediaMarkup += `![Image](${media.url})\n\n`;
                    }
                });
                
                textarea.value = textarea.value.substring(0, cursorPos) + mediaMarkup + textarea.value.substring(cursorPos);
                textarea.focus();
                textarea.setSelectionRange(cursorPos + mediaMarkup.length, cursorPos + mediaMarkup.length);
                updateCounts();
            }
            
            closeMediaLibrary();
        }
        
        function removeFeaturedImage() {
            document.getElementById('featured_image').value = '';
            document.getElementById('featured_image_preview').style.display = 'none';
            document.getElementById('remove_featured').style.display = 'none';
        }
        
        // File Upload
        const fileInput = document.getElementById('fileInput');
        const uploadArea = document.getElementById('uploadArea');
        
        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('drag-over');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            handleFiles(e.dataTransfer.files);
        });
        
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });
        
        function handleFiles(files) {
            for (let file of files) {
                uploadFile(file);
            }
        }
        
        function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'upload');
            
            const progressBar = document.getElementById('uploadProgress');
            const progressFill = document.getElementById('progressFill');
            
            progressBar.classList.add('active');
            
            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressFill.style.width = percentComplete + '%';
                    progressFill.textContent = percentComplete + '%';
                }
            });
            
            xhr.addEventListener('load', () => {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Reload media library after successful upload
                            switchMediaTab('library');
                            loadMediaLibrary();
                        } else {
                            alert('Upload failed: ' + (response.error || 'Unknown error'));
                        }
                    } catch (e) {
                        alert('Upload failed: Invalid response');
                    }
                } else {
                    alert('Upload failed: Server error');
                }
                
                progressBar.classList.remove('active');
                progressFill.style.width = '0%';
                progressFill.textContent = '0%';
                fileInput.value = '';
            });
            
            xhr.addEventListener('error', () => {
                alert('Upload failed: Network error');
                progressBar.classList.remove('active');
            });
            
            xhr.open('POST', '/blog/admin/upload.php');
            xhr.send(formData);
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
        
        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.getElementById('mediaLibraryModal').classList.contains('active')) {
                closeMediaLibrary();
            }
        });
    </script>
</body>
</html>