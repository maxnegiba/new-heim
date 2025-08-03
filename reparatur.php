<?php
// Setări SEO specifice pentru pagina Reparatur & Wartung
$page_title = "Dach Reparatur & Wartung Berlin Brandenburg | Notdienst";
$page_description = "Schnelle Dachreparaturen & professionelle Wartung in Berlin & Brandenburg. Kostenlose Inspektion, Notdienst 24/7. Jetzt anfragen!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/contact-float.php');
?>

<!-- HERO -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/services/services-hero.webp" alt="Dach Reparatur & Wartung" class="hero__bg" width="1920" height="1080">
  <div class="hero__overlay">
    <div class="container">
      <h1>Reparatur & Wartung</h1>
      <p>Schnelle Hilfe für Ihr Dach durch unsere Dachdecker</p>
    </div>
  </div>
</section>

<!-- Conținutul serviciului Reparatur -->
<section class="service-detail">
  <div class="container grid-2">
    <figure>
      <video controls preload="none" poster="<?= $assets_path ?>img/services/repair-poster.webp" aria-label="Video zur Dachreparatur">
        <source src="<?= $assets_path ?>video/repair.mp4" type="video/mp4">
        Ihr Browser unterstützt das Video-Tag nicht.
      </video>
    </figure>
    <div class="text">
      <h2>Kleine undichte Stelle oder kompletter Check-up</h2>
      <p>Kleine undichte Stelle oder kompletter Check-up durch unsere erfahrenen <strong>Dachdecker</strong> – wir richten alles wieder.</p>
      
      <h3>Unsere Reparatur- & Wartungsleistungen</h3>
      <ul>
        <li>✅ <strong>Kostenlose Erstinspektion</strong> vor Ort</li>
        <li>✅ Sofortige Behebung von Leckagen</li>
        <li>✅ <strong>Hochdruckreinigung</strong> zur Dachpflege</li>
        <li>✅ <strong>Notdienst 24/7</strong> bei Sturm- und Wetter-Schäden</li>
        <li>✅ Regelmäßige Wartung für maximale Dachlebensdauer</li>
      </ul>
      
      <a href="contact.php" class="btn btn--primary">Kostenlose Inspektion anfragen</a>
    </div>
  </div>
</section>

<!-- CTA Final -->
<section class="final-cta">
  <div class="container">
    <h2 class="section-title">Akute Dachprobleme?</h2>
    <p>Unsere erfahrenen <strong>Dachdecker</strong> in Berlin & Brandenburg helfen Ihnen schnell und zuverlässig. Kontaktieren Sie uns für einen kostenlosen Beratungstermin oder Notdienst.</p>
    <a href="contact.php" class="btn btn--primary btn--large">Jetzt kostenlos beraten lassen</a>
  </div>
</section>

<?php include(__DIR__ . '/includes/footer.php'); ?>
