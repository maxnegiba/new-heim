<?php
// Setări SEO specifice pentru pagina Bitumenschweißbahn
$page_title = "Bitumenschweißbahn Berlin Brandenburg | Dachdecker Meisterbetrieb";
$page_description = "Professionelle Bitumenschweißbahn für Flach- & Schrägdächer in Berlin & Brandenburg. 10 Jahre Garantie, langlebig & witterungsbeständig. Kostenlose Beratung!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/contact-float.php');
?>

<!-- HERO -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/services/services-hero.webp" alt="Bitumenschweißbahn Abdichtung Berlin" class="hero__bg" width="1920" height="1080">
  <div class="hero__overlay">
    <div class="container">
      <h1>Bitumenschweißbahn</h1>
      <p>Professionelle Abdichtung für Ihr Dach durch unsere Dachdecker</p>
    </div>
  </div>
</section>

<!-- Conținutul serviciului Bitumen -->
<section class="service-detail">
  <div class="container grid-2">
    <div class="text">
      <h2>Moderner Schutz für Ihr Dach</h2>
      <p>Moderne Abdichtung für Flach- und Schrägdächer durch unsere erfahrenen <strong>Dachdecker</strong> – 10 Jahre Garantie.</p>

      <h3>Vorteile unserer Bitumenschweißbahn</h3>
      <ul>
        <li>✅ <strong>Heißluft- oder Flammenverfahren</strong> für optimale Haftung</li>
        <li>✅ <strong>UV- und witterungsbeständig</strong> – hält extremen Bedingungen stand</li>
        <li>✅ <strong>Extrem langlebig</strong> – minimale Wartung nötig</li>
        <li>✅ Ideal für <strong>Dachsanierung</strong> und <strong>Neueindeckung</strong></li>
      </ul>

      <a href="contact.php" class="btn btn--primary">Kostenloses Angebot anfordern</a>
    </div>
    <figure>
      <video class="service-video video-thumb" controls preload="none" aria-label="Video zur Bitumenschweißbahn-Anwendung">
        <source src="<?= $assets_path ?>video/services/repartur.mp4" type="video/mp4">
        Ihr Browser unterstützt das Video-Tag nicht.
      </video>
      <figcaption>Bitumenschweißbahn wird fachgerecht aufgetragen</figcaption>
    </figure>
  </div>
</section>

<!-- Secțiune Galerie -->
<section class="service-gallery">
  <div class="container">
    <h2 class="section-title">Unsere Bitumenschweißbahn-Projekte</h2>

    <!-- IMAGINI – SWIPER -->
    <h3 class="section-subtitle">Fotogalerie (39 Bilder)</h3>
    <div class="swiper bitumen-swiper"> <!-- Clasa specifică pentru stiluri -->
      <div class="swiper-wrapper">
        <?php for ($i = 1; $i <= 39; $i++): ?>
          <a class="swiper-slide" href="<?= $assets_path ?>img/Neueindeckung/Neueindeckung<?= $i ?>.jpg" data-lightbox="bitumen-gallery">
            <img src="<?= $assets_path ?>img/Neueindeckung/Neueindeckung<?= $i ?>.jpg" loading="lazy" alt="Bitumenschweißbahn Projekt <?= $i ?> Berlin Brandenburg">
          </a>
        <?php endfor; ?>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>

    <!-- VIDEO – GRID LIGHTBOX -->
    <h3 class="section-subtitle">Videos (9 Clips)</h3>
    <div class="video-grid">
      <?php for ($v = 1; $v <= 9; $v++): ?>
        <a href="<?= $assets_path ?>video/neueindeckung/Neueindeckung<?= $v ?>.mp4" class="video-thumb" data-lightbox="bitumen-video">
          <video muted preload="metadata" aria-label="Video Clip Bitumenschweißbahn <?= $v ?>">
            <source src="<?= $assets_path ?>video/neueindeckung/Neueindeckung<?= $v ?>.mp4" type="video/mp4">
            Ihr Browser unterstützt das Video-Tag nicht.
          </video>
          <span class="play-icon">▶</span>
        </a>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- CTA Final -->
<section class="final-cta">
  <div class="container">
    <h2 class="section-title">Haben Sie Fragen zur Bitumenschweißbahn?</h2>
    <p>Kontaktieren Sie uns für eine kostenlose Beratung und ein individuelles Angebot für Ihr Projekt in Berlin oder Brandenburg.</p>
    <a href="contact.php" class="btn btn--primary btn--large">Jetzt kostenlos beraten lassen</a>
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
    const bitumenSwiperElement = document.querySelector('.bitumen-swiper');
    if (bitumenSwiperElement) {
        new Swiper(bitumenSwiperElement, {
        loop: true,
        spaceBetween: 16,
        pagination: {
          el: bitumenSwiperElement.querySelector('.swiper-pagination'),
          clickable: true,
        },
        navigation: {
          nextEl: bitumenSwiperElement.querySelector('.swiper-button-next'),
          prevEl: bitumenSwiperElement.querySelector('.swiper-button-prev'),
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
