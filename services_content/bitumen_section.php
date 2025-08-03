<!-- ===== BITUMEN ===== -->
<section id="bitumen" class="service-detail">
  <div class="container grid-2">
    <div class="text">
      <h2>Bitumenschweißbahn</h2>
      <p>Moderne Abdichtung für Flach- und Schrägdächer durch unsere erfahrenen <strong>Dachdecker</strong> – 10 Jahre Garantie.</p>
      <ul>
        <li>Heißluft- oder Flammenverfahren</li>
        <li>UV- und witterungsbeständig</li>
        <li>Extrem langlebig</li>
      </ul>
      <a href="contact.php" class="btn btn--primary">Angebot anfordern</a>
    </div>
    <figure>
      <video class="service-video video-thumb" controls preload="none">
        <source src="<?= $assets_path ?>video/services/repartur.mp4" type="video/mp4">
        Ihr Browser unterstützt das Video-Tag nicht.
      </video>
    </figure>
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

  <!-- VIDEO – GRID LIGHTBOX -->
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
