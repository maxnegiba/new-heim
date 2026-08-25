<?php
if (!isset($servicePage) || !is_array($servicePage)) {
    http_response_code(500);
    exit('Service configuration missing.');
}

$page_title = ($servicePage['title'] ?? 'Leistung') . ' | MB Bau Dienstleistungen Berlin';
$page_description = $servicePage['description'] ?? 'Leistung von MB Bau Dienstleistungen in Berlin und Umgebung.';
include __DIR__ . '/header.php';
?>

<section class="site-hero">
    <div class="container">
        <span class="hero-kicker">Leistung</span>
        <h1><?= htmlspecialchars((string) $servicePage['title']) ?></h1>
        <p><?= htmlspecialchars((string) ($servicePage['intro'] ?? '')) ?></p>
        <div class="hero-actions">
            <a href="/contact.php?service=<?= urlencode((string) $servicePage['contact_service']) ?>" class="btn btn--primary">Angebot anfragen</a>
            <a href="tel:+4917614122627" class="btn btn--ghost"><i class="fas fa-phone"></i> Anrufen</a>
        </div>
    </div>
</section>

<section class="section section--soft">
    <div class="container service-page-layout">
        <article class="service-page-main">
            <h2><?= htmlspecialchars((string) ($servicePage['heading'] ?? 'Was wir übernehmen')) ?></h2>
            <?php foreach (($servicePage['paragraphs'] ?? []) as $paragraph): ?>
                <p><?= htmlspecialchars((string) $paragraph) ?></p>
            <?php endforeach; ?>

            <?php if (!empty($servicePage['items'])): ?>
                <h2>Typische Arbeiten</h2>
                <ul class="check-list">
                    <?php foreach ($servicePage['items'] as $item): ?><li><?= htmlspecialchars((string) $item) ?></li><?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>

        <aside class="service-page-aside">
            <div class="icon-box"><i class="fas <?= htmlspecialchars((string) ($servicePage['icon'] ?? 'fa-hammer')) ?>"></i></div>
            <h2>Projekt besprechen</h2>
            <p>Schicken Sie uns Ort, kurze Beschreibung und – wenn möglich – einige Fotos.</p>
            <p><a href="tel:+4917614122627"><strong>+49 176 141 22 627</strong></a></p>
            <p><a href="https://wa.me/4917614122627" target="_blank" rel="noopener" class="btn btn--primary"><i class="fab fa-whatsapp"></i> WhatsApp</a></p>
            <p><a href="/services.php" class="card-link"><i class="fas fa-arrow-left"></i> Alle Leistungen</a></p>
        </aside>
    </div>
</section>

<section class="cta-band">
    <div class="container">
        <div><h2>Passende Lösung für Ihr Objekt</h2><p>Wir stimmen Umfang und nächsten Schritt direkt mit Ihnen ab.</p></div>
        <div class="cta-actions"><a href="/contact.php?service=<?= urlencode((string) $servicePage['contact_service']) ?>" class="btn btn--secondary">Anfrage senden</a></div>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>
