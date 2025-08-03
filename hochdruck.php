<?php
// Setări SEO specifice pentru pagina Hochdruckreiniger
$page_title = "Hochdruckreiniger & Nano-Versiegelung Berlin Brandenburg";
$page_description = "Professionelle Dachreinigung mit Hochdruckreiniger & Nano-Versiegelung in Berlin & Brandenburg. Schutz & Glanz für Ihr Dach. Kostenlose Beratung!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/contact-float.php');
?>

<!-- HERO -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/services/services-hero.webp" alt="Hochdruckreinigung Dach" class="hero__bg" width="1920" height="1080">
  <div class="hero__overlay">
    <div class="container">
      <h1>Hochdruckreiniger & Nano-Versiegelung</h1>
      <p>Professionelle Reinigung & langanhaltender Schutz</p>
    </div>
  </div>
</section>

<!-- Conținutul serviciului Hochdruck -->
<section class="service-detail">
 <div class="container">
   <h2>Ihr Fachbetrieb für Dachreinigung in Berlin & Brandenburg</h2>

 <div class="intro-block">
   <p>
     Professionelle Reinigung von Dächern, Fassaden, Steinflächen, PV-Anlagen und Graffiti-Entfernung –
     alles aus einer Hand. Mit <strong>Nano-Versiegelung</strong> für langanhaltenden Schutz.
   </p>
 </div>

 <!-- Benefits Grid -->
 <div class="benefits-grid">
   <div class="benefit-item">
     <div class="benefit-icon">💧</div>
     <h4>Umweltfreundlich</h4>
     <p>Ohne aggressive Chemikalien</p>
   </div>
   <div class="benefit-item">
     <div class="benefit-icon">⚡</div>
     <h4>Schnell & Effizient</h4>
     <p>In nur einem Tag erledigt</p>
   </div>
   <div class="benefit-item">
     <div class="benefit-icon">🛡️</div>
     <h4>Langanhaltender Schutz</h4>
     <p><strong>Nano-Versiegelung</strong> bis 5 Jahre</p>
   </div>
   <div class="benefit-item">
     <div class="benefit-icon">🏠</div>
     <h4>Mehrwert</h4>
     <p>Erhöht den Immobilienwert</p>
   </div>
 </div>

 <!-- Why Section -->
 <div class="hochdruck-why">
   <h3>Warum Dachreinigung?</h3>
   <ul class="check-list">
     <li>Subjektive Wertsteigerung Ihrer Immobilie</li>
     <li>Gepflegte und ansprechende Erscheinung</li>
     <li>Effektive Moos- und Algenentfernung</li>
     <li><strong>Nanobeschichtung</strong> gegen erneuten Bewuchs</li>
   </ul>
 </div>

 <!-- IMAGINI – SWIPER -->
 <h3 class="section-title">Reinigung – Galerie (6 Bilder)</h3>
 <div class="swiper hochdruck-swiper">
   <div class="swiper-wrapper">
     <?php for ($i = 1; $i <= 6; $i++): ?>
       <a class="swiper-slide" href="<?= $assets_path ?>img/hock/hock<?= $i ?>.jpg" data-lightbox="hochdruck-gallery">
         <img src="<?= $assets_path ?>img/hock/hock<?= $i ?>.jpg" loading="lazy" alt="Hochdruckreinigung Projekt <?= $i ?> Berlin">
       </a>
     <?php endfor; ?>
   </div>
   <div class="swiper-pagination"></div>
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
 </div>

 <!-- VIDEO – GRID -->
 <h3 class="section-title">Reinigung in Aktion (6 Videos)</h3>
 <div class="video-grid">
   <?php for ($v = 1; $v <= 6; $v++): ?>
     <a href="<?= $assets_path ?>video/hock/hock<?= $v ?>.mp4" class="video-thumb" data-lightbox="hochdruck-video">
       <video muted preload="metadata" poster="<?= $assets_path ?>img/hock/hock<?= $v ?>-poster.webp" aria-label="Video zur Hochdruckreinigung <?= $v ?>">
         <source src="<?= $assets_path ?>video/hock/hock<?= $v ?>.mp4" type="video/mp4">
         Ihr Browser unterstützt das Video-Tag nicht.
       </video>
       <span class="play-icon">▶</span>
     </a>
   <?php endfor; ?>
 </div>
 <a href="contact.php" class="btn btn--primary">Kostenlose Beratung anfragen</a>
</div> </section>

<!-- CTA Final -->
<section class="final-cta">
  <div class="container">
    <h2 class="section-title">Ihr Dach braucht eine Auffrischung?</h2>
    <p>Profitieren Sie von unserer professionellen <strong>Hochdruckreinigung</strong> und optionalen <strong>Nano-Versiegelung</strong> in Berlin & Brandenburg. Jetzt kostenlos beraten lassen!</p>
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
    const hochdruckSwiperElement = document.querySelector('.hochdruck-swiper');
    if (hochdruckSwiperElement) {
        new Swiper(hochdruckSwiperElement, {
        loop: true,
        spaceBetween: 16,
        pagination: {
          el: hochdruckSwiperElement.querySelector('.swiper-pagination'),
          clickable: true,
        },
        navigation: {
          nextEl: hochdruckSwiperElement.querySelector('.swiper-button-next'),
          prevEl: hochdruckSwiperElement.querySelector('.swiper-button-prev'),
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
