<?php
$page_title = 'Leistungen | MB Bau Dienstleistungen Berlin';
$page_description = 'Dacharbeiten, Reparaturen, Neueindeckung, Abdichtung, Dachfenster, Fassadenarbeiten und Zaunbau von MB Bau Dienstleistungen in Berlin und Umgebung.';
include(__DIR__ . '/includes/header.php');

$services = [
    ['id' => 'dacharbeiten', 'icon' => 'fa-house-chimney', 'title' => 'Dacharbeiten', 'text' => 'Arbeiten an Steil- und Flachdächern – vom kleineren Schaden bis zur umfassenden Instandsetzung.', 'items' => ['Dachreparaturen', 'Wartungs- und Instandhaltungsarbeiten', 'Arbeiten an Steil- und Flachdächern']],
    ['id' => 'reparatur', 'icon' => 'fa-screwdriver-wrench', 'title' => 'Reparatur & Wartung', 'text' => 'Beschädigte oder undichte Bereiche werden geprüft und passend zum vorhandenen Dach instand gesetzt.', 'items' => ['Undichte Stellen', 'Beschädigte Dachziegel', 'Sturm- und Witterungsschäden']],
    ['id' => 'neueindeckung', 'icon' => 'fa-layer-group', 'title' => 'Neueindeckung & Abdichtung', 'text' => 'Neue Dachflächen und Abdichtungsarbeiten werden auf Aufbau, Material und Nutzung abgestimmt.', 'items' => ['Neueindeckung', 'Bitumen- und Abdichtungsarbeiten', 'Anschluss- und Detailarbeiten']],
    ['id' => 'dachfenster', 'icon' => 'fa-window-maximize', 'title' => 'Dachfenster & Gauben', 'text' => 'Einbau und Arbeiten rund um Dachfenster und Gauben für mehr Licht, Funktion und nutzbaren Raum.', 'items' => ['Dachfenster', 'Gauben', 'Anschlussarbeiten am Dach']],
    ['id' => 'dachrinne', 'icon' => 'fa-water', 'title' => 'Dachrinnen & Metallarbeiten', 'text' => 'Arbeiten an Dachrinnen und wasserführenden Bauteilen helfen, Niederschlag kontrolliert vom Gebäude abzuleiten.', 'items' => ['Dachrinnen', 'Fallrohre', 'Kleinere Metall- und Anschlussarbeiten']],
    ['id' => 'fassade', 'icon' => 'fa-building', 'title' => 'Fassadenarbeiten', 'text' => 'Instandsetzung und Arbeiten an Fassaden mit Blick auf Schutz, Funktion und ein gepflegtes Erscheinungsbild.', 'items' => ['Ausbesserungsarbeiten', 'Fassadenbekleidungen', 'Anschluss- und Detailarbeiten']],
    ['id' => 'zaunbau', 'icon' => 'fa-border-all', 'title' => 'Zaunbau', 'text' => 'Montage und Erneuerung von Zaunanlagen für Grundstücke, Einfahrten und Außenbereiche.', 'items' => ['Zaunmontage', 'Erneuerung bestehender Anlagen', 'Individuelle Abstimmung vor Ort']],
    ['id' => 'reinigung', 'icon' => 'fa-spray-can-sparkles', 'title' => 'Dachreinigung & Pflege', 'text' => 'Reinigungs- und Pflegearbeiten können helfen, Verschmutzungen zu entfernen und den Zustand besser beurteilen zu können.', 'items' => ['Reinigung geeigneter Dachflächen', 'Sichtprüfung im Arbeitsbereich', 'Abstimmung weiterer Maßnahmen']],
];
?>

<section class="site-hero">
    <div class="container">
        <span class="hero-kicker">Leistungen</span>
        <h1>Arbeiten rund um Dach, Fassade und Grundstück</h1>
        <p>Wählen Sie den passenden Bereich oder senden Sie uns direkt Fotos und eine kurze Beschreibung Ihres Projekts.</p>
        <div class="hero-actions">
            <a href="/contact.php" class="btn btn--primary">Angebot anfragen</a>
            <a href="https://wa.me/4917614122627" target="_blank" rel="noopener" class="btn btn--ghost"><i class="fab fa-whatsapp"></i> Fotos per WhatsApp</a>
        </div>
    </div>
</section>

<nav class="service-nav-clean" aria-label="Leistungsbereiche">
    <div class="container">
        <?php foreach ($services as $service): ?>
            <a href="#<?= htmlspecialchars($service['id']) ?>"><?= htmlspecialchars($service['title']) ?></a>
        <?php endforeach; ?>
    </div>
</nav>

<section class="section section--soft">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Übersicht</span>
            <h2>Unsere Leistungsbereiche</h2>
            <p>Nicht sicher, welche Leistung passt? Beschreiben Sie einfach das Problem – wir ordnen es gemeinsam ein.</p>
        </div>
        <div class="service-list">
            <?php foreach ($services as $service): ?>
                <article class="service-panel" id="<?= htmlspecialchars($service['id']) ?>">
                    <div class="service-icon"><i class="fas <?= htmlspecialchars($service['icon']) ?>"></i></div>
                    <div>
                        <h2><?= htmlspecialchars($service['title']) ?></h2>
                        <p><?= htmlspecialchars($service['text']) ?></p>
                        <ul>
                            <?php foreach ($service['items'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <a href="/contact.php?service=<?= urlencode($service['title']) ?>" class="btn btn--secondary">Anfragen</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div class="media-panel">
            <img src="/assets/img/services/services-hero.webp" alt="Dacharbeiten in Berlin und Umgebung" loading="lazy" width="900" height="650">
        </div>
        <div class="content-block">
            <span class="eyebrow">Ablauf</span>
            <h2>So starten wir Ihr Projekt</h2>
            <p>Für eine erste Abstimmung reichen meist Ort, kurze Beschreibung und einige Fotos. Falls eine Besichtigung sinnvoll ist, klären wir den Termin direkt.</p>
            <ul class="check-list">
                <li>Kurze Beschreibung des Auftrags</li>
                <li>Fotos der betroffenen Stelle, wenn möglich</li>
                <li>Adresse oder Ort des Objekts</li>
                <li>Rückrufnummer und gewünschter Zeitraum</li>
            </ul>
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="container">
        <div><h2>Welche Arbeit steht bei Ihnen an?</h2><p>Wir klären den passenden nächsten Schritt direkt mit Ihnen.</p></div>
        <div class="cta-actions"><a href="tel:+4917614122627" class="btn btn--secondary">Anrufen</a><a href="/contact.php" class="btn btn--dark">Anfrage senden</a></div>
    </div>
</section>

<?php include(__DIR__ . '/includes/footer.php'); ?>
