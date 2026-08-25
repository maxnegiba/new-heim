<?php
// Setări SEO specifice pentru pagina Klempner
$page_title = "Klempner Berlin Brandenburg | Dachrinnen & Metallarbeiten";
$page_description = "Erfahrene Klempnerarbeiten in Berlin & Brandenburg. Dachrinnen, Metallarbeiten, Regenwasser-Systeme. Kostenlose Beratung & Angebot!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/contact-float.php');
?>

<!-- HERO -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/services/services-hero.webp" alt="Klempnerarbeiten Berlin Brandenburg" class="hero__bg" width="1920" height="1080">
  <div class="hero__overlay">
    <div class="container">
      <h1>Klempnerarbeiten</h1>
      <p>Maßgefertigte Dachrinnen & Metallarbeiten durch unsere Klempner</p>
    </div>
  </div>
</section>

<!-- Conținutul serviciului Klempner -->
<section class="service-detail">
  <div class="container">
    <h2>Maßgefertigte Dachrinnen</h2>
    <div class="intro-block">
      <p>Maßgefertigte Dachrinnen durch unsere erfahrenen <strong>Klempner</strong> – Kupfer, Zink oder Edelstahl – jede Rinne wird exakt auf Ihr Dach zugeschnitten. Inklusive Regenwasser-Einleitung, Laubschutz und 24-h-Reparaturservice.</p>
    </div>

    <!-- IMAGINI – SWIPER -->
    <h3 class="section-title">Klempner – Galerie (8 Bilder)</h3>
    <div class="swiper klempner-swiper">
      <div class="swiper-wrapper">
        <?php for ($i = 1; $i <= 8; $i++): ?>
          <a class="swiper-slide" href="<?= $assets_path ?>img/services/gutter-<?= $i ?>.webp" data-lightbox="klempner-gallery">
            <img src="<?= $assets_path ?>img/services/gutter-<?= $i ?>.webp" loading="lazy" alt="Dachrinne <?= $i ?> von erfahrenen Klempnern">
          </a>
        <?php endfor; ?>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>

    <!-- VIDEO – GRID LIGHTBOX -->
    <h3 class="section-title">Klempner – Videos (3 Clips)</h3>
    <div class="video-grid">
      <?php for ($v = 1; $v <= 3; $v++): ?>
        <a href="<?= $assets_path ?>video/services/gutter-install-<?= $v ?>.mp4" class="video-thumb" data-lightbox="klempner-video">
          <video muted preload="metadata" poster="<?= $assets_path ?>img/services/gutter-install-<?= $v ?>-poster.webp" aria-label="Video zur Dachrinnenmontage <?= $v ?>">
            <source src="<?= $assets_path ?>video/services/gutter-install-<?= $v ?>.mp4" type="video/mp4">
            Ihr Browser unterstützt das Video-Tag nicht.
          </video>
          <span class="play-icon">▶</span>
        </a>
      <?php endfor; ?>
    </div>
    <a href="contact.php" class="btn btn--primary">Defekt melden</a>
  </div>
</section>

<!-- CTA Final -->
<section class="final-cta">
  <div class="container">
    <h2 class="section-title">Benötigen Sie Klempnerdienste?</h2>
    <p>Unsere erfahrenen <strong>Klempner</strong> in Berlin & Brandenburg stehen Ihnen zur Verfügung. Kontaktieren Sie uns für ein kostenloses Angebot.</p>
    <a href="contact.php" class="btn btn--primary btn--large">Kostenlose Beratung anfragen</a>
  </div>
</section>

<?php include(__DIR__ . '/includes/footer.php'); ?>

<!-- ===== LIGHTBOX CSS/JS ===== -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

<!-- ===== SWIPER JS ===== -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const klempnerSwiperElement = document.querySelector('.klempner-swiper');
    if (klempnerSwiperElement) {
        new Swiper(klempnerSwiperElement, {
        loop: true,
        spaceBetween: 16,
        pagination: {
          el: klempnerSwiperElement.querySelector('.swiper-pagination'),
          clickable: true,
        },
        navigation: {
          nextEl: klempnerSwiperElement.querySelector('.swiper-button-next'),
          prevEl: klempnerSwiperElement.querySelector('.swiper-button-prev'),
        },
        breakpoints: {
          768: {
            slidesPerView: 1.2
          },
          1024: {
            slidesPerView: 1.4
          },
        }
      });
    }
  });
</script>
