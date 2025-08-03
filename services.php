<?php
$page_title = "Dienstleistungen | Dachdecker Berlin Brandenburg | Der Hausmeister Michael GmbH";
$page_description = "Alle Dachdecker-, Klempner- und Zimmermannsleistungen in Berlin & Brandenburg. Kostenlose Beratung & individuelles Angebot!";
$assets_path = 'assets/';
include(__DIR__ . '/includes/header.php');
include(__DIR__ . '/includes/floating-buttons.php');
?>

<!-- ===== HERO ===== -->
<section class="hero hero--small">
  <img src="<?= $assets_path ?>img/services/services-hero.webp" alt="Unsere Dienstleistungen" class="hero__bg" width="1920" height="1080">
  <div class="hero__overlay">
    <div class="container">
      <h1>Unsere Dienstleistungen</h1>
      <p>Alles rund ums Dach – Dachdecker, Klempner & Zimmermann aus einer Hand.</p> <!-- Actualizat -->
    </div>
  </div>
</section>

<!-- ===== QUICK NAV ===== -->
<nav class="service-nav" aria-label="Service-Sprungmarken">
  <div class="container">
    <a href="#bitumen">Bitumenschweißbahn</a>
    <a href="#reparatur">Reparatur & Pflege</a>
    <a href="#neu-eindeckung">Neueindeckung</a>
    <a href="#klempner">Klempner</a>
    <a href="#zimmerer">Zimmermann</a> <!-- ID-ul este 'zimmerer' -->
    <a href="#velux">Dachfenster</a>
    <a href="#gauben">Gauben</a>
    <a href="#hochdruck">Hochdruck</a>
    <a href="#antikondensation">Kondens & Asbest</a> <!-- Adăugat -->
  </div>
</nav>

<!-- ===== BITUMEN ===== -->
<?php include(__DIR__ . '/services_content/bitumen_section.php'); ?>

<!-- ===== REPARATUR ===== -->
<?php include(__DIR__ . '/services_content/reparatur_section.php'); ?>

<!-- ===== NEUEINDECKUNG ===== -->
<?php include(__DIR__ . '/services_content/neueindeckung_section.php'); ?>

<!-- ===== KLEMPNER ===== -->
<?php include(__DIR__ . '/services_content/klempner_section.php'); ?>

<!-- ===== ZIMMERMANN ===== -->
<?php include(__DIR__ . '/services_content/zimmerer_section.php'); ?> <!-- Corectat numele fișierului -->

<!-- ===== VELUX ===== -->
<?php include(__DIR__ . '/services_content/velux_section.php'); ?>

<!-- ===== GAUBEN ===== -->
<?php include(__DIR__ . '/services_content/gauben_section.php'); ?>

<!-- ===== HOCHDRUCK ===== -->
<?php include(__DIR__ . '/services_content/hochdruck_section.php'); ?>

<!-- ===== ANTIKONDENSATIONSFILZ ===== -->
<?php include(__DIR__ . '/services_content/antikondensation_section.php'); ?>

<?php include(__DIR__ . '/includes/footer.php'); ?>

<!-- ===== LIGHTBOX CSS/JS ===== -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>

<!-- ===== SWIPER JS ===== -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
 document.addEventListener('DOMContentLoaded', function() {
   // Inițializare Swiper pentru toate galeriile de imagini
   document.querySelectorAll('.swiper').forEach(el => {
     // Verificăm dacă Swiper nu a fost deja inițializat pentru acest element
     if (!el.swiper) {
         new Swiper(el, {
         loop: true,
         spaceBetween: 16,
         pagination: {
           el: el.querySelector('.swiper-pagination'),
           clickable: true,
         },
         navigation: {
           nextEl: el.querySelector('.swiper-button-next'),
           prevEl: el.querySelector('.swiper-button-prev'),
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
 });
</script>
