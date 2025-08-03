<!-- ===== NEUEINDECKUNG ===== -->
<section id="neu-eindeckung" class="service-detail">
 <div class="container">
   <h2>Neueindeckung</h2>
   <p> Wählen Sie aus Bitumenschweißbahn Tondach-ziegel oder Trapez-Blech.</p>
   <div class="material-chooser">
     <input type="radio" name="material" id="ziegel" checked>
     <label for="ziegel">
       <img src="<?= $assets_path ?>img/services/Bitumenschweinbahn.webp" alt="Ziegel">
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
   <a href="contact.php" class="btn btn--primary">Individuelles Angebot</a>
 </div>

 <!-- IMAGINI – SWIPER -->
 <h3 class="section-title">Neueindeckung – Galerie (39 Bilder)</h3>
 <div class="swiper neueindeckung-swiper">
   <div class="swiper-wrapper">
     <?php for ($i = 1; $i <= 39; $i++): ?>
       <a class="swiper-slide" href="<?= $assets_path ?>img/Neueindeckung/Neueindeckung<?= $i ?>.jpg" data-lightbox="neueindeckung">
         <img src="<?= $assets_path ?>img/Neueindeckung/Neueindeckung<?= $i ?>.jpg" loading="lazy" alt="Neueindeckung <?= $i ?>">
       </a>
     <?php endfor; ?>
   </div>
   <div class="swiper-pagination"></div>
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
 </div>
 <!-- VIDEO – GRID LIGHTBOX (uniformizat ca la Zimmermann) -->
 <h3 class="section-title">Neueindeckung – Videos (9 Clips)</h3>
 <div class="video-grid">
   <?php for ($v = 1; $v <= 9; $v++): ?>
     <a href="<?= $assets_path ?>video/neueindeckung/Neueindeckung<?= $v ?>.mp4" class="video-thumb" data-lightbox="neueindeckung-video">
       <video muted preload="metadata" >
         <source src="<?= $assets_path ?>video/neueindeckung/Neueindeckung<?= $v ?>.mp4" type="video/mp4">
         Ihr Browser unterstützt das Video-Tag nicht.
       </video>
       <span class="play-icon">▶</span>
     </a>
   <?php endfor; ?>
 </div>
 <a href="contact.php" class="btn btn--primary">Individuelles Angebot</a>
</div> </section>
