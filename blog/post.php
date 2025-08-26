<?php
// blog/post.php
require_once __DIR__ . '/../db.php';

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    http_response_code(400);
    header('Location: /blog');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ?");
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
    
    if (!$post) {
        http_response_code(404);
        $page_title = 'Beitrag nicht gefunden | Blog';
        $page_description = 'Der angeforderte Blog-Beitrag wurde nicht gefunden.';
        include 'includes/blog-header.php';
        echo '<div class="blog-container" style="margin-top: 120px; text-align: center; padding: 60px 20px;">';
        echo '<h1>Beitrag nicht gefunden</h1>';
        echo '<p>Der von Ihnen gesuchte Blog-Beitrag existiert nicht oder wurde entfernt.</p>';
        echo '<a href="/blog" class="btn-secondary" style="display: inline-block; margin-top: 20px;">Zurück zum Blog</a>';
        echo '</div>';
        include(__DIR__ . '/../../includes/footer.php');
        exit;
    }
    
    // Set SEO
    $page_title = htmlspecialchars($post['title']) . ' | Blog | Der Hausmeister Michael GmbH';
    $page_description = substr(strip_tags($post['body']), 0, 160);
    
} catch (PDOException $e) {
    http_response_code(500);
    $page_title = 'Fehler | Blog';
    include 'includes/blog-header.php';
    echo '<div class="blog-container" style="margin-top: 120px; text-align: center; padding: 60px 20px;">';
    echo '<h1>Serverfehler</h1>';
    echo '<p>Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.</p>';
    echo '<a href="/blog" class="btn-secondary" style="display: inline-block; margin-top: 20px;">Zurück zum Blog</a>';
    echo '</div>';
    include(__DIR__ . '/../../includes/footer.php');
    exit;
}

include 'includes/blog-header.php';
?>

<style>
.blog-post-hero {
    background: linear-gradient(135deg, rgba(211, 47, 47, 0.1), rgba(183, 28, 28, 0.1));
    padding: 80px 0 40px;
    margin-top: 100px;
    text-align: center;
}

.blog-post-hero h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.blog-post-meta {
    display: flex;
    justify-content: center;
    align-items: center;
    color: #7f8c8d;
    font-size: 1rem;
    margin-bottom: 30px;
}

.blog-post-meta i {
    margin-right: 8px;
}

.blog-post-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
}

.blog-post-content {
    background: white;
    border-radius: 10px;
    padding: 40px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    line-height: 1.8;
    color: #333;
}

.blog-post-content h2 {
    color: #2c3e50;
    margin: 30px 0 20px;
}

.blog-post-content h3 {
    color: #34495e;
    margin: 25px 0 15px;
}

.blog-post-content p {
    margin-bottom: 20px;
}

.blog-post-content ul, .blog-post-content ol {
    margin: 20px 0;
    padding-left: 30px;
}

.blog-post-content li {
    margin-bottom: 10px;
}

.back-to-blog {
    display: inline-block;
    margin: 30px 0;
    color: #d32f2f;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.back-to-blog:hover {
    color: #b71c1c;
}

.back-to-blog i {
    margin-right: 8px;
}

@media (max-width: 768px) {
    .blog-post-hero {
        padding: 60px 0 30px;
        margin-top: 80px;
    }
    
    .blog-post-hero h1 {
        font-size: 2rem;
    }
    
    .blog-post-container {
        margin: 30px auto;
    }
    
    .blog-post-content {
        padding: 25px 20px;
    }
}
</style>

<section class="blog-post-hero">
    <div class="container">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <div class="blog-post-meta">
            <i class="far fa-calendar"></i>
            <?php echo date('d.m.Y', strtotime($post['created_at'])); ?>
            <span style="margin: 0 15px;">•</span>
            <i class="far fa-clock"></i> <?php echo ceil(str_word_count($post['body']) / 200); ?> min Lesezeit
        </div>
    </div>
</section>

<div class="blog-post-container">
    <div class="blog-post-content">
        <?php echo nl2br(htmlspecialchars($post['body'])); ?>
    </div>
    
    <a href="/blog" class="back-to-blog">
        <i class="fas fa-arrow-left"></i> Zurück zum Blog
    </a>
</div>

<!-- Utterances comments -->
<script src="https://utteranc.es/client.js"
        repo="YOUR_GITHUB_USERNAME/hausmeistermichael-gmbh"
        issue-term="pathname"
        theme="github-light"
        crossorigin="anonymous"
        async>
</script>

<?php include(__DIR__ . '/../../includes/footer.php'); ?>