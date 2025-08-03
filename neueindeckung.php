<?php
// Setări SEO specifice pentru pagina Neueindeckung
$page_title = "Neue Dacheindeckung Berlin Brandenburg | Dachdecker";
$page_description = "Professionelle Neueindeckung mit Bitumenschweißbahn, Ziegeln oder Trapezblech in Berlin & Brandenburg. Kostenlose Beratung & Angebot!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/contact-float.php');
?>

<!-- HERO -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/services/services-hero.webp" alt="Neue Dacheindeckung" class="hero__bg" width="1920" height="1080">
  <div class="hero__overlay">
    <div class="container">
      <h1>Neueindeckung</h1>
      <p>Ihr neues Dach – fachgerecht & langlebig</p>
    </div>
  </div>
</section>

<!-- Conținutul serviciului Neueindeckung -->
<section class="service-detail">
 <div class="container">
   <h2>Wählen Sie Ihr Dachmaterial</h2>
   <p>Professionelle <strong>Neueindeckung</strong> durch unsere <strong>Dachdecker</strong> – Wählen Sie aus Bitumenschweißbahn, Tondach-Ziegel oder Trapez-Blech.</p>
   
   <div class="material-chooser">
     <input type="radio" name="material" id="ziegel" checked>
     <label for="ziegel">
       <img src="<?= $assets_path ?>img/services/Bitumenschweinbahn.webp" alt="Bitumenschweißbahn">
       <span>Bitumenschweißbahn</span>
     </label>
     <input type="radio" name="material" id="metal">
     <label for="metal">
       <img src="<?= $assets_path ?>img/services/trapez.webp" alt="Trapezblech">
       <span>Trapezblech</span>
     </label>
     <input type="radio" name="material" id="membrane">
     <label for="membrane">
       <img src="<?= $assets_path ?>img/services/metal.webp" alt="Membrane">
       <span>Membrane</span>
     </label>
   </div>
   <div id="price-preview" class="price-box">
     <small>inkl. Entsorgung & Montage</small>
   </div>
   <a href="contact.php" class="btn btn--primary">Individuelles Angebot anfordern</a>
 </div>

 <!-- IMAGINI – SWIPER -->
 <h3 class="section-title">Neueindeckung – Galerie (39 Bilder)</h3>
 <div class="swiper neueindeckung-swiper">
   <div class="swiper-wrapper">
     <?php for ($i = 1; $i <= 39; $i++): ?>
       <a class="swiper-slide" href="<?= $assets_path ?>img/Neueindeckung/Neueindeckung<?= $i ?>.jpg" data-lightbox="neueindeckung-gallery">
         <img src="<?= $assets_path ?>img/Neueindeckung/Neueindeckung<?= $i ?>.jpg" loading="lazy" alt="Neue Dacheindeckung Projekt <?= $i ?> Berlin">
       </a>
     <?php endfor; ?>
   </div>
   <div class="swiper-pagination"></div>
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
 </div>

 <!-- VIDEO – GRID LIGHTBOX -->
 <h3 class="section-title">Neueindeckung – Videos (9 Clips)</h3>
 <div class="video-grid">
   <?php for ($v = 1; $v <= 9; $v++): ?>
     <a href="<?= $assets_path ?>video/neueindeckung/Neueindeckung<?= $v ?>.mp4" class="video-thumb" data-lightbox="neueindeckung-video">
       <video muted preload="metadata" aria-label="Video zur Neueindeckung <?= $v ?>">
         <source src="<?= $assets_path ?>video/neueindeckung/Neueindeckung<?= $v ?>.mp4" type="video/mp4">
         Ihr Browser unterstützt das Video-Tag nicht.
       </video>
       <span class="play-icon">▶</span>
     </a>
   <?php endfor; ?>
 </div>
 <a href="contact.php" class="btn btn--primary">Individuelles Angebot anfordern</a>
</section>

<!-- CTA Final -->
<section class="final-cta">
  <div class="container">
    <h2 class="section-title">Planen Sie Ihr neues Dach?</h2>
    <p>Unsere erfahrenen <strong>Dachdecker</strong> in Berlin & Brandenburg erstellen Ihnen gerne ein kostenloses, individuelles Angebot für Ihre <strong>Neueindeckung</strong>.</p>
    <a href="contact.php" class="btn btn--primary btn--large">Kostenloses Angebot anfragen</a>
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
    const neueindeckungSwiperElement = document.querySelector('.neueindeckung-swiper');
    if (neueindeckungSwiperElement) {
        new Swiper(neueindeckungSwiperElement, {
        loop: true,
        spaceBetween: 16,
        pagination: {
          el: neueindeckungSwiperElement.querySelector('.swiper-pagination'),
          clickable: true,
        },
        navigation: {
          nextEl: neueindeckungSwiperElement.querySelector('.swiper-button-next'),
          prevEl: neueindeckungSwiperElement.querySelector('.swiper-button-prev'),
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
