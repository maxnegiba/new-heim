<!-- ===== VELUX ===== -->
<section id="velux" class="service-detail alt">
 <div class="container">
   <h2>Velux Dachfenster</h2>
   <p>Mehr Licht & frische Luft – Einbau in nur einem Tag.</p>
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
       <a class="swiper-slide" href="<?= $assets_path ?>img/velux/velux<?= $i ?>.jpg" data-lightbox="velux">
         <img src="<?= $assets_path ?>img/velux/velux<?= $i ?>.jpg" loading="lazy" alt="Velux <?= $i ?>">
       </a>
     <?php endfor; ?>
   </div>
   <div class="swiper-pagination"></div>
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
 </div>
 <!-- VIDEO – GRID LIGHTBOX (uniformizat ca la Zimmermann) -->
 <h3 class="section-title">Velux – Videos (3 Clips)</h3>
 <div class="video-grid">
   <?php for ($v = 1; $v <= 3; $v++): ?>
     <a href="<?= $assets_path ?>video/velux/velux<?= $v ?>.mp4" class="video-thumb" data-lightbox="velux-video">
       <video muted preload="metadata">
         <source src="<?= $assets_path ?>video/velux/velux<?= $v ?>.mp4" type="video/mp4">
         Ihr Browser unterstützt das Video-Tag nicht.
       </video>
       <span class="play-icon">▶</span>
     </a>
   <?php endfor; ?>
 </div>
 <a href="contact.php" class="btn btn--primary">Modell wählen</a>
</div> </section>
