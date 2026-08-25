<?php
require_once __DIR__ . '/../db.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$post = null;

if ($slug !== '' && $pdo instanceof PDO) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ? AND status = 'published' LIMIT 1");
        $stmt->execute([$slug]);
        $post = $stmt->fetch() ?: null;

        if ($post) {
            $update = $pdo->prepare('UPDATE blog_posts SET views = COALESCE(views, 0) + 1 WHERE id = ?');
            $update->execute([$post['id']]);
        }
    } catch (PDOException $exception) {
        error_log('MB Bau blog post query failed: ' . $exception->getMessage());
    }
}

if (!$post) {
    http_response_code(404);
    $page_title = 'Beitrag nicht gefunden | MB Bau Dienstleistungen';
    $page_description = 'Der angeforderte Blog-Beitrag ist derzeit nicht verfügbar.';
    include __DIR__ . '/includes/blog-header.php';
    ?>
    <section class="site-hero">
        <div class="container"><span class="hero-kicker">Blog</span><h1>Beitrag nicht gefunden</h1><p>Der angeforderte Beitrag ist derzeit nicht verfügbar.</p></div>
    </section>
    <section class="section"><div class="container"><div class="empty-state"><i class="fas fa-file-circle-xmark"></i><h2>Zurück zur Übersicht</h2><p>Im Blog finden Sie alle aktuell veröffentlichten Beiträge.</p><a href="/blog/" class="btn btn--primary">Zum Blog</a></div></div></section>
    <?php
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$title = (string) ($post['title'] ?? 'Blog-Beitrag');
$rawBody = (string) ($post['body'] ?? '');
$page_title = trim((string) ($post['meta_title'] ?? '')) ?: $title . ' | MB Bau Dienstleistungen';
$page_description = trim((string) ($post['meta_description'] ?? '')) ?: mb_strimwidth(trim(strip_tags($rawBody)), 0, 160, '…', 'UTF-8');

function renderSimpleMarkdown(string $markdown): string
{
    $safe = htmlspecialchars($markdown, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $safe = preg_replace('/^###\s+(.+)$/m', '<h3>$1</h3>', $safe) ?? $safe;
    $safe = preg_replace('/^##\s+(.+)$/m', '<h2>$1</h2>', $safe) ?? $safe;
    $safe = preg_replace('/^#\s+(.+)$/m', '<h2>$1</h2>', $safe) ?? $safe;
    $safe = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $safe) ?? $safe;
    $safe = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $safe) ?? $safe;
    $safe = preg_replace_callback('/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/', static function (array $match): string {
        return '<a href="' . htmlspecialchars($match[2], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener">' . $match[1] . '</a>';
    }, $safe) ?? $safe;
    return nl2br($safe, false);
}

include __DIR__ . '/includes/blog-header.php';
?>

<section class="site-hero">
    <div class="container">
        <span class="hero-kicker">Blog</span>
        <h1><?= htmlspecialchars($title) ?></h1>
        <div class="blog-meta" style="color:rgba(255,255,255,.72);margin-top:18px">
            <span><i class="far fa-calendar"></i> <?= date('d.m.Y', strtotime((string) $post['created_at'])) ?></span>
            <span><i class="far fa-eye"></i> <?= number_format((int) ($post['views'] ?? 0), 0, ',', '.') ?> Aufrufe</span>
        </div>
    </div>
</section>

<section class="section section--soft">
    <div class="container article-wrap">
        <article class="article-card">
            <?php if (!empty($post['featured_image'])): ?>
                <img src="<?= htmlspecialchars((string) $post['featured_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($title) ?>" loading="eager">
            <?php endif; ?>
            <?= renderSimpleMarkdown($rawBody) ?>
        </article>
        <p style="margin-top:24px"><a href="/blog/" class="card-link"><i class="fas fa-arrow-left"></i> Zurück zum Blog</a></p>
    </div>
</section>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
