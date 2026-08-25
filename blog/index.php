<?php
require_once __DIR__ . '/../db.php';

$page_title = 'Blog | MB Bau Dienstleistungen Berlin';
$page_description = 'Tipps, Einblicke und Informationen rund um Dacharbeiten, Fassade und Bauprojekte von MB Bau Dienstleistungen.';
$posts = [];

if ($pdo instanceof PDO) {
    try {
        $stmt = $pdo->query("SELECT id, title, slug, body, excerpt, featured_image, created_at, views FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC");
        $posts = $stmt->fetchAll();
    } catch (PDOException $exception) {
        error_log('MB Bau blog query failed: ' . $exception->getMessage());
    }
}

include __DIR__ . '/includes/blog-header.php';
?>

<section class="site-hero">
    <div class="container">
        <span class="hero-kicker">Blog</span>
        <h1>Wissen rund ums Dach und Gebäude</h1>
        <p>Praktische Hinweise, Einblicke in Arbeiten und Themen, die bei Reparatur, Pflege und Sanierung wichtig werden können.</p>
    </div>
</section>

<section class="section section--soft">
    <div class="container">
        <?php if (!$posts): ?>
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h2>Neue Beiträge sind in Vorbereitung</h2>
                <p>Bis dahin finden Sie unsere Leistungen und Projektbilder direkt auf der Website.</p>
                <div class="hero-actions" style="justify-content:center;margin:22px 0 0">
                    <a href="/services.php" class="btn btn--primary">Leistungen ansehen</a>
                    <a href="/projects.php" class="btn btn--secondary">Projekte ansehen</a>
                </div>
            </div>
        <?php else: ?>
            <div class="blog-grid">
                <?php foreach ($posts as $post): ?>
                    <?php
                    $excerpt = trim((string) ($post['excerpt'] ?? ''));
                    if ($excerpt === '') {
                        $excerpt = trim(strip_tags((string) ($post['body'] ?? '')));
                    }
                    $excerpt = mb_strimwidth($excerpt, 0, 180, '…', 'UTF-8');
                    $postUrl = '/blog/post.php?slug=' . rawurlencode((string) $post['slug']);
                    ?>
                    <article class="blog-card">
                        <a href="<?= htmlspecialchars($postUrl, ENT_QUOTES, 'UTF-8') ?>" class="blog-card__media" aria-label="<?= htmlspecialchars((string) $post['title']) ?>">
                            <?php if (!empty($post['featured_image'])): ?>
                                <img src="<?= htmlspecialchars((string) $post['featured_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) $post['title']) ?>" loading="lazy">
                            <?php else: ?>
                                <i class="fas fa-file-lines"></i>
                            <?php endif; ?>
                        </a>
                        <div class="blog-card__body">
                            <div class="blog-meta">
                                <span><i class="far fa-calendar"></i> <?= date('d.m.Y', strtotime((string) $post['created_at'])) ?></span>
                                <?php if (!empty($post['views'])): ?><span><i class="far fa-eye"></i> <?= number_format((int) $post['views'], 0, ',', '.') ?></span><?php endif; ?>
                            </div>
                            <h2><a href="<?= htmlspecialchars($postUrl, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $post['title']) ?></a></h2>
                            <p class="blog-excerpt"><?= htmlspecialchars($excerpt) ?></p>
                            <a class="card-link" href="<?= htmlspecialchars($postUrl, ENT_QUOTES, 'UTF-8') ?>">Weiterlesen <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
