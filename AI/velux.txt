<?php
// Setări SEO specifice pentru pagina Velux
$page_title = "Velux Dachfenster Berlin Brandenburg | Dachdecker";
$page_description = "Professionelle Montage von Velux Dachfenstern in Berlin & Brandenburg. Mehr Licht & Frischluft. Kostenlose Beratung!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/contact-float.php');
?>

<!-- HERO -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/services/services-hero.webp" alt="Velux Dachfenster Montage" class="hero__bg" width="1920" height="1080">
  <div class="hero__overlay">
    <div class="container">
      <h1>Velux Dachfenster</h1>
      <p>Mehr Licht & Frischluft durch Velux</p>
    </div>
  </div>
</section>

<!-- Conținutul serviciului Velux -->
<section class="service-detail">
 <div class="container">
   <h2>Mehr Licht & frische Luft</h2>
   <p>Mehr Licht & frische Luft durch <strong>Dachfenster</strong> von Velux – Einbau in nur einem Tag durch unsere <strong>Dachdecker</strong>.</p>

 <div class="velux-grid">
   <div>
     <h3>Modelle</h3>
     <ul>
       <li>Top-Hung</li>
       <li>Center-Pivot</li>
       <li>Elektrisch / Solar</li>
     </ul>
   </div>
   <div>
     <h3>Extras</h3>
     <ul>
       <li>Verdunkelungs-Rollo</li>
       <li>Insektenschutz</li>
       <li>Smart-Home-ready</li>
     </ul>
   </div>
 </div>

 <!-- IMAGINI – SWIPER -->
 <h3 class="section-title">Velux – Galerie (6 Bilder)</h3>
 <div class="swiper velux-swiper">
   <div class="swiper-wrapper">
     <?php for ($i = 1; $i <= 6; $i++): ?>
       <a class="swiper-slide" href="<?= $assets_path ?>img/velux/velux<?= $i ?>.jpg" data-lightbox="velux-gallery">
         <img src="<?= $assets_path ?>img/velux/velux<?= $i ?>.jpg" loading="lazy" alt="Velux Dachfenster <?= $i ?> Berlin">
       </a>
     <?php endfor; ?>
   </div>
   <div class="swiper-pagination"></div>
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
 </div>

 <!-- VIDEO – GRID LIGHTBOX -->
 <h3 class="section-title">Velux – Videos (3 Clips)</h3>
 <div class="video-grid">
   <?php for ($v = 1; $v <= 3; $v++): ?>
     <a href="<?= $assets_path ?>video/velux/velux<?= $v ?>.mp4" class="video-thumb" data-lightbox="velux-video">
       <video muted preload="metadata" aria-label="Video zur Velux-Montage <?= $v ?>">
         <source src="<?= $assets_path ?>video/velux/velux<?= $v ?>.mp4" type="video/mp4">
         Ihr Browser unterstützt das Video-Tag nicht.
       </video>
       <span class="play-icon">▶</span>
     </a>
   <?php endfor; ?>
 </div>
 <a href="contact.php" class="btn btn--primary">Modell & Angebot wählen</a>
</div> </section>

<!-- CTA Final -->
<section class="final-cta">
  <div class="container">
    <h2 class="section-title">Wünschen Sie sich mehr Licht in Ihren Räumen?</h2>
    <p>Unsere <strong>Dachdecker</strong> in Berlin & Brandenburg montieren Ihr neues <strong>Velux Dachfenster</strong> schnell und fachgerecht. Kontaktieren Sie uns!</p>
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
    const veluxSwiperElement = document.querySelector('.velux-swiper');
    if (veluxSwiperElement) {
        new Swiper(veluxSwiperElement, {
        loop: true,
        spaceBetween: 16,
        pagination: {
          el: veluxSwiperElement.querySelector('.swiper-pagination'),
          clickable: true,
        },
        navigation: {
          nextEl: veluxSwiperElement.querySelector('.swiper-button-next'),
          prevEl: veluxSwiperElement.querySelector('.swiper-button-prev'),
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
