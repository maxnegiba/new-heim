<!-- ===== GAUBEN ===== -->
<section id="gauben" class="service-detail">
 <div class="container">
   <h2>Gauben</h2>
   <div class="intro-block">
     <h3>Mehr Raum, mehr Licht</h3>
     <p> Sie gewinnen viel Raum, haben eine zusätzliche Lüftungsmöglichkeit und Lichtquelle. Verschiedene Formen: Schleppdach, Satteldach, Trapezdach u.v.m. </p>
   </div>
 <!-- IMAGINI – SWIPER -->
 <h3 class="section-title">Gauben – Galerie (8 Bilder)</h3>
 <div class="swiper gauben-swiper">
   <div class="swiper-wrapper">
     <?php for ($i = 1; $i <= 8; $i++): ?>
       <a class="swiper-slide" href="<?= $assets_path ?>img/gauben/gauben-<?= $i ?>.jpg" data-lightbox="gauben">
         <img src="<?= $assets_path ?>img/gauben/gauben-<?= $i ?>.jpg" loading="lazy" alt="Gaube <?= $i ?>">
       </a>
     <?php endfor; ?>
   </div>
   <div class="swiper-pagination"></div>
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
 </div>
 <!-- VIDEO – GRID LIGHTBOX (uniformizat ca la Zimmermann) -->
 <h3 class="section-title">Gauben – Videos (4 Clips)</h3>
 <div class="video-grid">
   <?php for ($v = 1; $v <= 4; $v++): ?>
     <a href="<?= $assets_path ?>video/gauben/gauben-<?= $v ?>.mp4" class="video-thumb" data-lightbox="gauben-video">
       <video muted preload="metadata" poster="<?= $assets_path ?>img/gauben/video<?= $v ?>-poster.webp">
         <source src="<?= $assets_path ?>video/gauben/gauben-<?= $v ?>.mp4" type="video/mp4">
         Ihr Browser unterstützt das Video-Tag nicht.
       </video>
       <span class="play-icon">▶</span>
     </a>
   <?php endfor; ?>
 </div>
 <a href="contact.php" class="btn btn--primary">Beratung anfragen</a>
</div> </section>
