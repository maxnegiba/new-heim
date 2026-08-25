<?php
// Setări SEO specifice pentru pagina Zimmermann
$page_title = "Zimmermann Berlin Brandenburg | Holzbau & Dachstuhl";
$page_description = "Qualifizierte Zimmermannsarbeiten in Berlin & Brandenburg. Holzbau, Dachstühle, Carports, Terrassen. Kostenlose Beratung!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/contact-float.php');
?>

<!-- HERO -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/services/services-hero.webp" alt="Zimmermann Holzbau Berlin Brandenburg" class="hero__bg" width="1920" height="1080">
  <div class="hero__overlay">
    <div class="container">
      <h1>Zimmermann & Holzbau</h1>
      <p>Massive Holzbauten nach Maß durch unsere Zimmerleute</p>
    </div>
  </div>
</section>

<!-- Conținutul serviciului Zimmermann -->
<section class="service-detail">
  <div class="container">
    <h2>Massive Holzbauten nach Maß</h2>
    <div class="intro-block">
      <p>Massive Holzbauten nach Maß durch unsere qualifizierten <strong>Zimmerleute</strong> – Carports, Terrassenüberdachungen, Dachstühle – alles aus FSC-zertifiziertem Holz, inklusive statischer Berechnung und Aufbau in 3 Tagen.</p>
    </div>

    <!-- IMAGINI – SWIPER -->
    <h3 class="section-title">Zimmermann – Galerie (6 Bilder)</h3>
    <div class="swiper zimmermann-swiper">
      <div class="swiper-wrapper">
        <?php for ($i = 1; $i <= 6; $i++): ?>
          <a class="swiper-slide" href="<?= $assets_path ?>img/zim/zim<?= $i ?>.jpg" data-lightbox="zimmermann-gallery">
            <img src="<?= $assets_path ?>img/zim/zim<?= $i ?>.jpg" loading="lazy" alt="Zimmermann Projekt <?= $i ?> - Holzbau">
          </a>
        <?php endfor; ?>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>

    <!-- VIDEO – GRID LIGHTBOX -->
    <h3 class="section-title">Zimmermann – Videos (6 Clips)</h3>
    <div class="video-grid">
      <?php for ($v = 1; $v <= 6; $v++): ?>
        <a href="<?= $assets_path ?>video/services/zim<?= $v ?>.mp4" class="video-thumb" data-lightbox="zimmermann-video">
          <video muted preload="metadata" aria-label="Video zum Zimmermann-Projekt <?= $v ?>">
            <source src="<?= $assets_path ?>video/services/zim<?= $v ?>.mp4" type="video/mp4">
            Ihr Browser unterstützt das Video-Tag nicht.
          </video>
          <span class="play-icon">▶</span>
        </a>
      <?php endfor; ?>
    </div>
    <a href="contact.php" class="btn btn--primary">Kostenloser Termin</a>
  </div>
</section>

<!-- CTA Final -->
<section class="final-cta">
  <div class="container">
    <h2 class="section-title">Planen Sie ein Holzprojekt?</h2>
    <p>Unsere erfahrenen <strong>Zimmerleute</strong> aus Berlin & Brandenburg erstellen Ihnen gerne ein individuelles Angebot. Kontaktieren Sie uns!</p>
    <a href="contact.php" class="btn btn--primary btn--large">Kostenlose Planung anfragen</a>
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
    const zimmermannSwiperElement = document.querySelector('.zimmermann-swiper');
    if (zimmermannSwiperElement) {
        new Swiper(zimmermannSwiperElement, {
        loop: true,
        spaceBetween: 16,
        pagination: {
          el: zimmermannSwiperElement.querySelector('.swiper-pagination'),
          clickable: true,
        },
        navigation: {
          nextEl: zimmermannSwiperElement.querySelector('.swiper-button-next'),
          prevEl: zimmermannSwiperElement.querySelector('.swiper-button-prev'),
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
