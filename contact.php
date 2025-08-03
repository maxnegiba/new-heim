<?php
// Setări SEO pentru pagina de contact
$page_title = "Kontakt | Dachdecker Berlin Brandenburg | Der Hausmeister Michael GmbH";
$page_description = "Kontaktieren Sie uns für professionelle Dachdecker-, Klempner- und Zimmermannsarbeiten in Berlin & Brandenburg. Kostenlose Beratung & Angebot!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
// include(__DIR__ . '/includes/contact-float.php'); // Dacă este folosit, poate fi inclus mai jos
?>

<!-- ===== HERO ===== -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/contact-hero.jpg" alt="Team am Dach" class="hero__bg" width="1920" height="1080"> <!-- Adăugat dimensiuni pentru SEO -->
  <div class="hero__overlay">
    <div class="container">
      <h1>Kontakt – Wir freuen uns auf Ihre Anfrage.</h1>
    </div>
  </div>
</section>

<!-- ===== 3 CĂI RAPIDE ===== -->
<section class="quick-contact">
  <div class="container grid-3">
    <a href="tel:+491626781242" class="quick-card">
      <i class="fas fa-phone"></i>
      <h3>Rufen Sie uns an</h3>
      <span>+49 162 678 12 42</span>
    </a>

    <a href="https://wa.me/491626781242?text=Guten%20Tag%2C%20ich%20m%C3%B6chte%20gerne%20ein%20Angebot%20f%C3%BCr%20mein%20Dach%20anfragen" target="_blank" class="quick-card">
      <i class="fab fa-whatsapp"></i>
      <h3>WhatsApp</h3>
      <span>+49 162 678 1 242</span>
    </a>

    <a href="mailto:info@hausmeistermichael-gmbh.de" class="quick-card"> <!-- Eliminat rândul nou din email -->
      <i class="fas fa-envelope"></i>
      <h3>E-Mail</h3>
      <span id="mail">info@hausmeistermichael-gmbh.de</span> <!-- Eliminat rândul nou din email -->
    </a>
  </div>
</section>

<!-- ===== FORMULAR ===== -->
<section id="contact-form" class="form-section">
  <div class="container">
    <h2>Anfrageformular</h2>

    <form action="php/send-mail.php" method="POST" id="ajaxForm" novalidate>
      <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

      <div class="grid-2">
        <label>
          Vor- & Nachname *
          <input type="text" name="name" required>
        </label>

        <label>
          Telefon
          <input type="tel" name="phone">
        </label>

        <label>
          E-Mail
          <input type="email" name="email">
        </label>

        <label>
          Adresse des Objekts *
          <input type="text" name="address" required placeholder="Straße, PLZ, Ort (z.B. für Dachsanierung)"> <!-- Adăugat placeholder cu cuvânt cheie -->
        </label>

        <label>
          Art der Dienstleistung *
          <select name="service" required>
            <option value="">Bitte wählen</option>
            <option value="Neueindeckung">Neueindeckung</option>
            <option value="Dachsanierung">Dachsanierung</option>
            <option value="Dämmung">Dämmung</option>
            <option value="Solarmontage">Solarmontage</option>
            <option value="Notdienst">Notdienst</option>
            <option value="Klempnerarbeiten">Klempnerarbeiten</option> <!-- Adăugat opțiune cu cuvânt cheie -->
            <option value="Zimmermannsarbeiten">Zimmermannsarbeiten</option> <!-- Adăugat opțiune cu cuvânt cheie -->
          </select>
        </label>

        <label>
          Wunschtermin
          <input type="date" name="date">
        </label>
      </div>

      <label>
        Nachricht / weitere Angaben
        <textarea name="message" rows="4" placeholder="z.B. Dachdeckerarbeiten, Klempner Notdienst..."></textarea> <!-- Adăugat placeholder cu cuvinte cheie -->
      </label>

      <label class="gdpr">
        <input type="checkbox" name="gdpr" required>
        Ich stimme der <a href="privacy.php" target="_blank">Datenschutzerklärung</a> zu. *
      </label>

      <!-- honeypot -->
      <input type="text" name="website" style="display:none">

      <button type="submit" class="btn btn--primary">
        <span>Absenden</span>
        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
      </button>

      <p class="form-msg"></p>
    </form>
  </div>
</section>

<!-- ===== HARTĂ + PROGRAM ===== -->
<section class="map-info">
  <div class="container grid-2">
    <div class="map-wrapper">
      <!-- Actualizează acest iframe cu linkul corect de la Google Maps pentru adresa ta -->
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2481.824208195582!2d13.548492377414365!3d52.53499997199464!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47a84e3712222223%3A0x41d85a5f5f5f5f5f!2sLandsberger%20Allee%20366%2C%2012681%20Berlin!5e0!3m2!1sde!2sde!4v1731075678509!5m2!1sde!2sde"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        aria-label="Lageplan Der Hausmeister Michael GmbH, Berlin"
      ></iframe>
      <!-- Actualizează acest link cu linkul corect de la Google Maps pentru adresa ta -->
      <a class="map-link" href="https://www.google.com/maps/dir//Landsberger+Allee+366,+12681+Berlin" target="_blank">→ Route planen</a>
    </div>

    <div class="address">
      <h3>Adresse & Öffnungszeiten</h3>
      <p>Landsberger Allee 366<br> 12681 Berlin <br> Deutschland</p>
      <table>
        <tr><td>Mo – Fr:</td><td>07:00 – 17:00</td></tr>
        <tr><td>Sa:</td><td>08:00 – 12:00</td></tr>
        <tr><td>So:</td><td>Geschlossen</td></tr>
      </table>
      <p><em>Nur 15 min von Berlin Zentrum</em></p>
    </div>
  </div>
</section>

<!-- ===== FAQ ===== -->
<section class="faq">
  <div class="container">
    <h2>Häufige Fragen</h2>
    <details>
      <summary>Wie lange dauert eine Dachsanierung?</summary>
      <p>Je nach Größe des <strong>Dachdecker</strong>-Projekts 5–10 Arbeitstage.</p> <!-- Integrat cuvânt cheie -->
    </details>
    <details>
      <summary>Können Sie auch Solaranlagen montieren?</summary>
      <p>Ja – wir sind zertifiziert für Photovoltaik- und Solarthermie-Systeme, ideal für <strong>Zimmermann</strong>- und <strong>Klempner</strong>-Integration.</p> <!-- Integrat cuvinte cheie -->
    </details>
    <details>
      <summary>Welche Materialien verwenden Sie?</summary>
      <p>Überwiegend Ziegel, Betonsteine, Metall und moderne Kunststoffe, je nach Anforderung des <strong>Handwerker</strong>-Auftrags.</p> <!-- Integrat cuvânt cheie -->
    </details>
    <details>
      <summary>Gibt es eine Garantie?</summary>
      <p>Mindestens 5 Jahre – gerne Details im Angebot.</p>
    </details>
  </div>
</section>

<!-- ===== NOTDIENST BANNER ===== -->
<?php
$now = (int) date('Hi');
$open = ($now >= 700 && $now < 1700) || ($now >= 800 && $now < 1200 && date('N') == 6);
if (!$open): ?>
<div class="emergency-banner">
  <div class="container">
    <i class="fas fa-exclamation-triangle"></i>
    <span><strong>Notdienst:</strong> +49 162 678 12 42</span>
    <a href="sms:+491626781242?body=Notdienst%20Anfrage" class="btn btn--small" aria-label="SMS an Notdienst senden">SMS senden</a> <!-- Adăugat aria-label -->
  </div>
</div>
<?php endif; ?>

<!-- ===== MICRO-CONVERSII ===== -->
<section class="extras">
  <div class="container grid-2">
    <div class="download-box">
      <h3>PDF-Katalog</h3>
      <p>Fordern Sie unseren kostenlosen Katalog an.</p>
      <button class="btn btn--outline" id="catalogBtn">Download</button>
    </div>
    <div class="newsletter-box">
      <h3>Newsletter</h3>
      <p>Tipps & Aktionen rund ums Dach.</p>
      <form id="newsletterForm">
        <input type="email" placeholder="Ihre E-Mail" required>
        <button type="submit" class="btn btn--outline">Anmelden</button>
      </form>
    </div>
  </div>
</section>

<!-- ===== SCRIPTS ===== -->
<script src="https://www.google.com/recaptcha/api.js?render=6Lfd6IwrAAAAAAq9AnqQDYHssazEzrcAQaCfQLDj"></script>
<script type="module">
  import {initContactForm} from '<?= $assets_path ?>js/modules/contact-form.js';
  initContactForm();
</script>

<!-- ===== SCHEMA MARKUP (OPȚIONAL) ===== -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Der Hausmeister Michael GmbH",
  "image": "https://<?= $_SERVER['HTTP_HOST'] ?>/assets/img/logo-text.jpg",
  "telephone": "+49 162 678 12 42",
  "email": "info@hausmeistermichael-gmbh.de",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Landsberger Allee 366",
    "addressLocality": "Berlin",
    "postalCode": "12681",
    "addressCountry": "DE"
  },
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday"
      ],
      "opens": "07:00",
      "closes": "17:00"
    },
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": "Saturday",
      "opens": "08:00",
      "closes": "12:00"
    }
  ],
  "sameAs": [
    // Adaugă profilul tău de Google Business aici dacă ai
  ]
}
</script>

<?php include(__DIR__ . '/includes/footer.php'); ?>