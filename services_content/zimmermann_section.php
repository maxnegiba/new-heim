<!-- ===== ZIMMERMANN ===== -->
<section id="zimmerer" class="service-detail">
 <div class="container">
   <h2>Zimmermann & Holzbau</h2>
   <div class="intro-block">
     <h3>Massive Holzbauten nach Maß</h3>
     <p> Carports, Terrassenüberdachungen, Dachstühle – alles aus FSC-zertifiziertem Holz, inklusive statischer Berechnung und Aufbau in 3 Tagen. </p>
   </div>
 <!-- IMAGINI – SWIPER -->
 <h3 class="section-title">Zimmermann – Galerie (6 Bilder)</h3>
 <div class="swiper zimmermann-swiper">
   <div class="swiper-wrapper">
     <?php for ($i = 1; $i <= 6; $i++): ?>
       <a class="swiper-slide" href="<?= $assets_path ?>img/zim/zim<?= $i ?>.jpg" data-lightbox="zimmermann">
         <img src="<?= $assets_path ?>img/zim/zim<?= $i ?>.jpg" loading="lazy" alt="Zimmermann Projekt <?= $i ?>">
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
     <a href="<?= $assets_path ?>video/services/zim<?= $v ?>.mp4" class="video-thumb" data-lightbox="zimm-video">
       <video muted preload="metadata">
         <source src="<?= $assets_path ?>video/services/zim<?= $v ?>.mp4" type="video/mp4">
         Ihr Browser unterstützt das Video-Tag nicht.
       </video>
       <span class="play-icon">▶</span>
     </a>
   <?php endfor; ?>
 </div>
 <a href="contact.php" class="btn btn--primary">Kostenloser Termin</a>
</div> </section>
