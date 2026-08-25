<?php
$page_title = 'Projekte | MB Bau Dienstleistungen Berlin';
$page_description = 'Einblicke in ausgeführte Arbeiten von MB Bau Dienstleistungen in Berlin und Umgebung.';
include(__DIR__ . '/includes/header.php');

$folder = __DIR__ . '/assets/img/projects';
$webBase = '/assets/img/projects/';
$projects = [];

if (is_dir($folder)) {
    $thumbs = glob($folder . '/*_thumb.jpg') ?: [];
    sort($thumbs, SORT_NATURAL | SORT_FLAG_CASE);

    foreach ($thumbs as $thumb) {
        $thumbName = basename($thumb);
        $baseName = substr($thumbName, 0, -strlen('_thumb.jpg'));
        $candidates = [
            $baseName . '@2x.webp',
            $baseName . '@2x.jpg',
            $baseName . '.webp',
            $baseName . '.jpg',
        ];
        $fullName = null;
        foreach ($candidates as $candidate) {
            if (is_file($folder . '/' . $candidate)) {
                $fullName = $candidate;
                break;
            }
        }
        if ($fullName) {
            $projects[] = ['thumb' => $thumbName, 'full' => $fullName];
        }
    }

    if (!$projects) {
        $fallback = array_merge(glob($folder . '/*.webp') ?: [], glob($folder . '/*.jpg') ?: []);
        foreach ($fallback as $file) {
            $name = basename($file);
            if (str_contains($name, '@2x') || str_contains($name, '_thumb')) {
                continue;
            }
            $projects[] = ['thumb' => $name, 'full' => $name];
        }
    }
}
?>

<section class="site-hero">
    <div class="container">
        <span class="hero-kicker">Projekte</span>
        <h1>Einblicke in unsere Arbeiten</h1>
        <p>Eine Auswahl aus Dach- und Bauarbeiten. Klicken Sie auf ein Bild, um es größer anzusehen.</p>
    </div>
</section>

<section class="section section--soft">
    <div class="container">
        <?php if (!$projects): ?>
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <h2>Projektgalerie wird vorbereitet</h2>
                <p>Aktuell sind hier noch keine Bilder verfügbar. Gerne zeigen wir Ihnen passende Referenzen im direkten Gespräch.</p>
                <a href="/contact.php" class="btn btn--primary">Kontakt aufnehmen</a>
            </div>
        <?php else: ?>
            <div class="project-grid">
                <?php foreach (array_slice($projects, 0, 30) as $index => $project): ?>
                    <?php
                    $thumbUrl = $webBase . rawurlencode($project['thumb']);
                    $fullUrl = $webBase . rawurlencode($project['full']);
                    $label = 'Projekt ' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                    ?>
                    <a class="project-card" href="<?= htmlspecialchars($fullUrl, ENT_QUOTES, 'UTF-8') ?>" data-lightbox="mb-projekte" data-title="<?= htmlspecialchars($label) ?>">
                        <img src="<?= htmlspecialchars($thumbUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($label) ?> – MB Bau Dienstleistungen" loading="lazy" decoding="async">
                        <span class="project-label"><?= htmlspecialchars($label) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="cta-band">
    <div class="container">
        <div><h2>Ähnliches Projekt geplant?</h2><p>Schicken Sie uns Fotos und eine kurze Beschreibung – wir melden uns zur Abstimmung.</p></div>
        <div class="cta-actions"><a href="https://wa.me/4917614122627" target="_blank" rel="noopener" class="btn btn--secondary"><i class="fab fa-whatsapp"></i> WhatsApp</a><a href="/contact.php" class="btn btn--dark">Anfrage senden</a></div>
    </div>
</section>

<?php include(__DIR__ . '/includes/footer.php'); ?>
