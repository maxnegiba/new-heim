<?php
$page_title = 'Kontakt | MB Bau Dienstleistungen Berlin';
$page_description = 'Kontaktieren Sie MB Bau Dienstleistungen für Dacharbeiten, Fassadenarbeiten und Zaunbau in Berlin und Umgebung. Telefon und WhatsApp: +49 176 141 22 627.';
$requestedService = trim((string) ($_GET['service'] ?? ''));
include(__DIR__ . '/includes/header.php');

$serviceOptions = [
    'Dacharbeiten',
    'Reparatur & Wartung',
    'Neueindeckung & Abdichtung',
    'Dachfenster & Gauben',
    'Dachrinnen & Metallarbeiten',
    'Fassadenarbeiten',
    'Zaunbau',
    'Dachreinigung & Pflege',
    'Sonstiges',
];
?>

<section class="site-hero">
    <div class="container">
        <span class="hero-kicker">Kontakt</span>
        <h1>Erzählen Sie uns kurz, worum es geht</h1>
        <p>Telefonisch, per WhatsApp oder über das Anfrageformular. Fotos helfen uns bei einer ersten Einschätzung.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container contact-actions">
        <a href="tel:+4917614122627" class="contact-card">
            <div class="icon-box"><i class="fas fa-phone"></i></div>
            <div><h3>Direkt anrufen</h3><p>+49 176 141 22 627</p></div>
        </a>
        <a href="https://wa.me/4917614122627?text=Guten%20Tag%2C%20ich%20m%C3%B6chte%20eine%20Anfrage%20zu%20meinem%20Projekt%20senden." target="_blank" rel="noopener" class="contact-card">
            <div class="icon-box"><i class="fab fa-whatsapp"></i></div>
            <div><h3>WhatsApp</h3><p>Nachricht und Fotos senden</p></div>
        </a>
    </div>
</section>

<section class="section section--soft">
    <div class="container form-layout">
        <form action="/php/send-mail.php" method="POST" id="ajaxForm" class="form-card">
            <h2>Anfrage senden</h2>
            <p>Felder mit * sind erforderlich. Mindestens Telefon oder E-Mail angeben.</p>
            <input type="hidden" name="started_at" value="<?= time() ?>">

            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Vor- &amp; Nachname *</label>
                    <input id="name" type="text" name="name" autocomplete="name" maxlength="120" required>
                </div>
                <div class="form-field">
                    <label for="phone">Telefon</label>
                    <input id="phone" type="tel" name="phone" autocomplete="tel" maxlength="40">
                </div>
                <div class="form-field">
                    <label for="email">E-Mail</label>
                    <input id="email" type="email" name="email" autocomplete="email" maxlength="160">
                </div>
                <div class="form-field">
                    <label for="address">Ort / Adresse des Objekts *</label>
                    <input id="address" type="text" name="address" autocomplete="street-address" maxlength="220" placeholder="Straße, PLZ, Ort" required>
                </div>
                <div class="form-field">
                    <label for="service">Leistung *</label>
                    <select id="service" name="service" required>
                        <option value="">Bitte wählen</option>
                        <?php foreach ($serviceOptions as $option): ?>
                            <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" <?= $requestedService === $option ? 'selected' : '' ?>><?= htmlspecialchars($option) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="date">Wunschtermin</label>
                    <input id="date" type="date" name="date">
                </div>
                <div class="form-field form-field--full">
                    <label for="message">Nachricht</label>
                    <textarea id="message" name="message" maxlength="3000" placeholder="Was soll gemacht werden? Gibt es Schäden oder Besonderheiten?"></textarea>
                </div>
            </div>

            <div class="hp-field" aria-hidden="true">
                <label for="website">Website</label>
                <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <label class="consent-row">
                <input type="checkbox" name="consent" value="1" required>
                <span>Ich bin damit einverstanden, dass meine Angaben zur Bearbeitung dieser Anfrage verwendet werden. *</span>
            </label>

            <button type="submit" class="btn btn--primary">
                <span>Anfrage absenden</span>
                <i class="fas fa-spinner fa-spin" hidden></i>
            </button>
            <p class="form-msg" role="status" aria-live="polite"></p>
        </form>

        <aside class="contact-side-card">
            <h2>Für eine schnelle Einschätzung</h2>
            <ul class="check-list">
                <li>Ort oder Adresse des Objekts</li>
                <li>Kurze Beschreibung der gewünschten Arbeit</li>
                <li>Fotos der betroffenen Stelle, wenn möglich</li>
                <li>Rückrufnummer und gewünschter Zeitraum</li>
            </ul>
            <p style="margin-top:24px">Am schnellsten erreichen Sie uns telefonisch oder per WhatsApp.</p>
            <p><a href="tel:+4917614122627">+49 176 141 22 627</a></p>
        </aside>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Häufige Fragen</span>
            <h2>Vor der Anfrage</h2>
        </div>
        <div class="faq-list">
            <details><summary>Welche Informationen soll ich senden?</summary><p>Am hilfreichsten sind Ort, kurze Beschreibung und einige Fotos. Damit lässt sich oft schon einschätzen, welcher nächste Schritt sinnvoll ist.</p></details>
            <details><summary>Kann ich Fotos per WhatsApp schicken?</summary><p>Ja. Senden Sie die Bilder direkt an +49 176 141 22 627 und schreiben Sie kurz dazu, worum es geht.</p></details>
            <details><summary>Arbeiten Sie auch außerhalb Berlins?</summary><p>Wir sind in Berlin und im Umland tätig. Senden Sie uns den Ort des Objekts, dann klären wir die Einsatzmöglichkeit direkt.</p></details>
        </div>
    </div>
</section>

<script type="module">
    import { initContactForm } from '/assets/js/modules/contact-form.js?v=20260825';
    initContactForm();
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "HomeAndConstructionBusiness",
  "name": "MB Bau Dienstleistungen",
  "url": "https://dachdeckerberlin24.de/",
  "image": "https://dachdeckerberlin24.de/assets/img/logo-mb-bau.svg",
  "telephone": "+4917614122627",
  "areaServed": "Berlin und Umgebung"
}
</script>

<?php include(__DIR__ . '/includes/footer.php'); ?>
