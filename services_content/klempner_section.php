<!-- ===== KLEMPNER ===== -->
<section id="klempner" class="service-detail alt">
 <div class="container">
   <h2>Klempnerarbeiten</h2>
   <div class="intro-block">
     <h3>Maßgefertigte Dachrinnen</h3>
     <p> Kupfer, Zink oder Edelstahl – jede Rinne wird exakt auf Ihr Dach zugeschnitten. Inklusive Regenwasser-Einleitung, Laubschutz und 24-h-Reparaturservice. </p>
   </div>
 <!-- IMAGINI – SWIPER -->
 <h3 class="section-title">Klempner – Galerie (8 Bilder)</h3>
 <div class="swiper klempner-swiper">
   <div class="swiper-wrapper">
     <?php for ($i = 1; $i <= 8; $i++): ?>
       <a class="swiper-slide" href="<?= $assets_path ?>img/services/gutter-<?= $i ?>.webp" data-lightbox="klempner">
         <img src="<?= $assets_path ?>img/services/gutter-<?= $i ?>.webp" loading="lazy" alt="Dachrinne <?= $i ?>">
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
       <video muted preload="metadata" poster="<?= $assets_path ?>img/services/gutter-install-<?= $v ?>-poster.webp">
         <source src="<?= $assets_path ?>video/services/gutter-install-<?= $v ?>.mp4" type="video/mp4">
         Ihr Browser unterstützt das Video-Tag nicht.
       </video>
       <span class="play-icon">▶</span>
     </a>
   <?php endfor; ?>
 </div>
 <a href="contact.php" class="btn btn--primary">Defekt melden</a>
</div> </section>
