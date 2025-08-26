<?php
require_once __DIR__ . '/../db.php';

// Setări SEO pentru blog
$page_title = 'Blog | Dachdecker Berlin Brandenburg | Der Hausmeister Michael GmbH';
$page_description = 'Neueste Nachrichten, Tipps und Projekte aus der Welt der Dachdecker, Klempner und Zimmerleute.';

try {
    $stmt = $pdo->query("SELECT id, title, slug, body, created_at FROM blog_posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
    error_log("Blog error: " . $e->getMessage());
}

include 'includes/blog-header.php';
?>

<style>
.blog-hero {
    background: linear-gradient(135deg, rgba(211, 47, 47, 0.1), rgba(183, 28, 28, 0.1));
    padding: 80px 0 40px;
    margin-top: 100px;
    text-align: center;
}

.blog-hero h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.blog-hero p {
    font-size: 1.2rem;
    color: #7f8c8d;
    max-width: 700px;
    margin: 0 auto;
}

.blog-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.blog-post-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #eee;
}

.blog-post-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.blog-post-image {
    height: 200px;
    background: linear-gradient(45deg, #d32f2f, #b71c1c);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.blog-post-content {
    padding: 25px;
}

.blog-post-content h3 {
    margin: 0 0 15px 0;
    color: #2c3e50;
}

.blog-post-content h3 a {
    text-decoration: none;
    color: inherit;
    transition: color 0.3s ease;
}

.blog-post-content h3 a:hover {
    color: #d32f2f;
}

.blog-post-meta {
    display: flex;
    align-items: center;
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.blog-post-meta i {
    margin-right: 8px;
}

.blog-post-excerpt {
    color: #555;
    line-height: 1.6;
    margin-bottom: 20px;
}

.read-more {
    display: inline-block;
    color: #d32f2f;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.read-more:hover {
    color: #b71c1c;
}

.read-more i {
    margin-left: 5px;
    transition: transform 0.3s ease;
}

.read-more:hover i {
    transform: translateX(3px);
}

.no-posts {
    text-align: center;
    padding: 60px 20px;
    color: #7f8c8d;
}

.no-posts i {
    font-size: 3rem;
    margin-bottom: 20px;
    color: #d32f2f;
}

@media (max-width: 768px) {
    .blog-hero {
        padding: 60px 0 30px;
        margin-top: 80px;
    }
    
    .blog-hero h1 {
        font-size: 2rem;
    }
    
    .blog-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .blog-post-content {
        padding: 20px;
    }
}
</style>

<section class="blog-hero">
    <div class="container">
        <h1>Unser Blog</h1>
        <p>Fachwissen, Projekte und Tipps aus der Welt der Dachdecker, Klempner und Zimmerleute</p>
    </div>
</section>

<div class="blog-container">
    <?php if (empty($posts)): ?>
        <div class="no-posts">
            <i class="fas fa-newspaper"></i>
            <h3>Noch keine Blog-Beiträge</h3>
            <p>Wir arbeiten daran, interessante Inhalte für Sie zu erstellen.</p>
        </div>
    <?php else: ?>
        <div class="blog-grid">
            <?php foreach ($posts as $post): ?>
                <article class="blog-post-card">
                    <div class="blog-post-image">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="blog-post-content">
                        <h3>
                            <a href="/blog/<?php echo htmlspecialchars($post['slug']); ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <div class="blog-post-meta">
                            <i class="far fa-calendar"></i>
                            <?php echo date('d.m.Y', strtotime($post['created_at'])); ?>
                        </div>
                        <div class="blog-post-excerpt">
                            <?php 
                            $excerpt = strip_tags($post['body']);
                            echo htmlspecialchars(substr($excerpt, 0, 150)) . (strlen($excerpt) > 150 ? '...' : '');
                            ?>
                        </div>
                        <a href="/blog/<?php echo htmlspecialchars($post['slug']); ?>" class="read-more">
                            Weiterlesen <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include(__DIR__ . '/includes/footer.php'); ?>