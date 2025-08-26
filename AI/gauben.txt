<?php
// Setări SEO specifice pentru pagina Gauben
$page_title = "Gauben Dachausbau Berlin Brandenburg | Zimmerer";
$page_description = "Individuelle Gauben & Dachausbauten in Berlin & Brandenburg. Mehr Raum & Licht. Kostenlose Planung & Beratung!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/contact-float.php');
?>

<!-- HERO -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/services/services-hero.webp" alt="Gauben Dachausbau" class="hero__bg" width="1920" height="1080">
  <div class="hero__overlay">
    <div class="container">
      <h1>Gauben & Dachausbau</h1>
      <p>Mehr Raum & Licht durch individuelle Lösungen</p>
    </div>
  </div>
</section>

<!-- Conținutul serviciului Gauben -->
<section class="service-detail">
 <div class="container">
   <h2>Mehr Raum, mehr Licht</h2>
   <div class="intro-block">
     <p>Mehr Raum, mehr Licht durch individuelle <strong>Gauben</strong> – Sie gewinnen viel Raum, haben eine zusätzliche Lüftungsmöglichkeit und Lichtquelle. Verschiedene Formen: Schleppdach, Satteldach, Trapezdach u.v.m.</p>
   </div>

 <!-- IMAGINI – SWIPER -->
 <h3 class="section-title">Gauben – Galerie (8 Bilder)</h3>
 <div class="swiper gauben-swiper">
   <div class="swiper-wrapper">
     <?php for ($i = 1; $i <= 8; $i++): ?>
       <a class="swiper-slide" href="<?= $assets_path ?>img/gauben/gauben-<?= $i ?>.jpg" data-lightbox="gauben-gallery">
         <img src="<?= $assets_path ?>img/gauben/gauben-<?= $i ?>.jpg" loading="lazy" alt="Gauben Dachausbau Projekt <?= $i ?> Brandenburg">
       </a>
     <?php endfor; ?>
   </div>
   <div class="swiper-pagination"></div>
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
 </div>

 <!-- VIDEO – GRID LIGHTBOX -->
 <h3 class="section-title">Gauben – Videos (4 Clips)</h3>
 <div class="video-grid">
   <?php for ($v = 1; $v <= 4; $v++): ?>
     <a href="<?= $assets_path ?>video/gauben/gauben-<?= $v ?>.mp4" class="video-thumb" data-lightbox="gauben-video">
       <video muted preload="metadata" poster="<?= $assets_path ?>img/gauben/video<?= $v ?>-poster.webp" aria-label="Video zum Gaubenbau <?= $v ?>">
         <source src="<?= $assets_path ?>video/gauben/gauben-<?= $v ?>.mp4" type="video/mp4">
         Ihr Browser unterstützt das Video-Tag nicht.
       </video>
       <span class="play-icon">▶</span>
     </a>
   <?php endfor; ?>
 </div>
 <a href="contact.php" class="btn btn--primary">Beratung & Planung anfragen</a>
</div> </section>

<!-- CTA Final -->
<section class="final-cta">
  <div class="container">
    <h2 class="section-title">Planen Sie einen Dachausbau?</h2>
    <p>Unsere erfahrenen <strong>Zimmerleute</strong> aus Berlin & Brandenburg planen und bauen Ihre individuellen <strong>Gauben</strong> und Dachausbauten. Kostenlose Beratung!</p>
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
    const gaubenSwiperElement = document.querySelector('.gauben-swiper');
    if (gaubenSwiperElement) {
        new Swiper(gaubenSwiperElement, {
        loop: true,
        spaceBetween: 16,
        pagination: {
          el: gaubenSwiperElement.querySelector('.swiper-pagination'),
          clickable: true,
        },
        navigation: {
          nextEl: gaubenSwiperElement.querySelector('.swiper-button-next'),
          prevEl: gaubenSwiperElement.querySelector('.swiper-button-prev'),
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
