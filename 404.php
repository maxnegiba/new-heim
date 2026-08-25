<?php
http_response_code(404);
$page_title = 'Seite nicht gefunden | MB Bau Dienstleistungen';
$page_description = 'Die angeforderte Seite wurde nicht gefunden.';
include __DIR__ . '/includes/header.php';
?>
<section class="site-hero">
    <div class="container">
        <span class="hero-kicker">404</span>
        <h1>Diese Seite gibt es hier nicht</h1>
        <p>Der Link ist möglicherweise veraltet oder die Adresse wurde falsch eingegeben.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="empty-state">
            <i class="fas fa-compass"></i>
            <h2>Zurück zu MB Bau Dienstleistungen</h2>
            <p>Auf der Startseite finden Sie unsere wichtigsten Leistungen und Kontaktmöglichkeiten.</p>
            <div class="hero-actions" style="justify-content:center;margin:22px 0 0">
                <a href="/" class="btn btn--primary">Zur Startseite</a>
                <a href="/contact.php" class="btn btn--secondary">Kontakt</a>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
