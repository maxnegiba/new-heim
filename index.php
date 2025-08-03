<?php
// Setări SEO pentru pagina principală
$page_title = "Dachdecker Berlin Brandenburg | Klempner & Zimmermann | Der Hausmeister Michael GmbH";
$page_description = "Professionelle Dachdecker, Klempner & Zimmermann in Berlin & Brandenburg. Über 20 Jahre Erfahrung. Kostenlose Beratung & Angebot!";

include(__DIR__ . '/includes/header.php');
// include(__DIR__ . '/includes/contact-float.php'); // Dacă este folosit, poate fi inclus mai jos
?>

<!-- CSS Critic pentru Homepage -->
<style>
/* Conținutul CSS-ului critic generat de tine */
@import url('base/variables.css');@import url('base/reset.css');@import url('base/typography.css');@import url('base/animations.css');@import url('base/variables.css');@import url('base/animations.css');@import url('components/header.css');@import url('components/carousel.css');@import url('components/footer.css');@import url('components/buttons.css');@import url('utilities/responsive.css');*{margin:0;padding:0;box-sizing:border-box}body{font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;line-height:1.6;color:#333;background:#f8f9fa;overflow-x:hidden}.hero-section{position:relative;min-height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden;padding-top:var(--header-height);box-sizing:border-box;background:#000}.hero-video{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:var(--z-hero-video)}.hero-overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:var(--z-hero-overlay)}.hero-content{position:relative;z-index:var(--z-hero-content);text-align:center;max-width:1200px;width:90%;padding:2rem;color:white}.hero-content h1{font-size:clamp(2.5rem,5vw,4rem);margin-bottom:1rem;animation:fadeInUp 1s ease-out;line-height:1.2;text-shadow:0 2px 10px rgba(0,0,0,0.3)}.hero-content h2{font-size:clamp(1.5rem,3vw,2.5rem);font-weight:400;margin-bottom:1.5rem;animation:fadeInUp 1s ease-out 0.2s forwards;opacity:0;line-height:1.3}.hero-content p{font-size:clamp(1.1rem,2vw,1.25rem);max-width:800px;margin:0 auto 2rem;animation:fadeInUp 1s ease-out 0.4s forwards;opacity:0;text-shadow:0 1px 3px rgba(0,0,0,0.5)}.cta-button{display:inline-block;background:linear-gradient(135deg,var(--primary-color) 0%,var(--primary-dark) 100%);color:white;padding:1rem 2.5rem;border-radius:50px;font-weight:600;font-size:1.1rem;text-decoration:none;box-shadow:var(--shadow-primary);animation:fadeInUp 1s ease-out 0.6s forwards;opacity:0;border:none}.trust-badges{display:flex;justify-content:center;gap:2rem;flex-wrap:wrap;margin-top:2rem;animation:fadeInUp 1s ease-out 0.8s forwards;opacity:0}.trust-badges span{display:flex;align-items:center;gap:0.5rem;font-size:0.9rem;background:rgba(255,255,255,0.1);backdrop-filter:blur(10px);padding:0.5rem 1rem;border-radius:30px;border:1px solid rgba(255,255,255,0.2)}.scroll-indicator{position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);z-index:var(--z-hero-content);animation:scroll 2s infinite;width:30px;height:50px;border-radius:15px;border:2px solid white;display:flex;justify-content:center;padding-top:10px}.scroll-indicator span{display:block;width:8px;height:8px;background:white;border-radius:50%;animation:scrollBounce 2s infinite}@keyframes fadeInUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}@keyframes scroll{0%{opacity:0.5;transform:translateY(0) translateX(-50%)}50%{opacity:1;transform:translateY(-10px) translateX(-50%)}100%{opacity:0.5;transform:translateY(0) translateX(-50%)}}@keyframes scrollBounce{0%,100%{transform:translateY(0)}50%{transform:translateY(5px)}}@media (max-width:768px){.hero-section{min-height:90vh}.hero-content{padding:1rem}.hero-content h1{font-size:2.2rem}.hero-content h2{font-size:1.4rem}.trust-badges{gap:0.8rem}.trust-badges span{font-size:0.8rem;padding:0.4rem 0.8rem}}@media (max-width:480px){.hero-section{min-height:85vh}.hero-content h1{font-size:1.8rem}.hero-content h2{font-size:1.2rem}.hero-content p{font-size:1rem}.trust-badges{flex-direction:column;gap:0.5rem}.cta-button{padding:0.8rem 2rem;font-size:1rem}.scroll-indicator{bottom:1rem}}@media (prefers-reduced-motion:reduce){*{animation-duration:0.01ms!important;animation-iteration-count:1!important}}
</style>

<!-- HERO SECTION -->
<section class="hero-section">
    <video class="hero-video"
           autoplay
           muted
           loop
           playsinline
           preload="metadata"
           poster="<?= $assets_path ?>images/hero-video.webp">
        <source src="<?= $assets_path ?>video/hero-video.webm" type="video/webm">
        <source src="<?= $assets_path ?>video/hero-video.mp4" type="video/mp4">
    </video>
    <!-- Overlay -->
    <div class="hero-overlay"></div>
    <!-- Content -->
    <div class="hero-content">
        <h1>Herzlich Willkommen bei Der Hausmeister Michael GmbH</h1>
        <h2>Wir schützen Ihr Eigentum im Neubau und Bestand durch das traditionelle Dachdeckerhandwerk.</h2>
        <p>
            Sind wir Ihr richtiger Ansprechpartner?<br>
            Unser Ziel ist es, Ihre Wünsche umzusetzen!<br>
            Im Laufe der Zeit muss ein Dach Regen, Sturm, Schnee und Hitze standhalten. Eine optimale Dachkonstruktion bietet dabei den notwendigen Schutz. Überlassen Sie die Qualität der Bedachung nicht dem Zufall, sondern kompetenten Fachkräften.<br>
            Wir von der Dachdeckerei Michael sind sowohl für Privatkunden als auch für Unternehmen, Architekten und öffentliche Auftraggeber (Bund, Länder und Gemeinden) ein kompetenter Ansprechpartner für Bedachungen aller Art. Und mit Erfolg sind wir für zahlreiche Kunden im Raum <strong>Berlin, Brandenburg, Potsdam und Frankfurt (Oder)</strong> tätig.<br>
            <strong>Dachdecker in Berlin & Brandenburg</strong> - von der kleinsten Dachreparatur bis zur kompletten Dacheindeckung.
            <br><strong>Über 20 Jahre Erfahrung.</strong>
            <br>Bund, Länder, Gemeinden & Privatkunden vertrauen uns.
        </p>
        <a href="contact.php" class="cta-button">Jetzt unverbindlich anfragen</a>
        <!-- Trust Badges -->
        <div class="trust-badges">
            <span>🏆 20+ Jahre Meisterbetrieb</span>
            <span>📍 Berlin · Potsdam · Frankfurt (Oder)</span>
            <span>✅ Zertifiziert für öffentliche Aufträge</span>
        </div>
    </div>
    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <span></span>
    </div>
</section>

 <!-- SERVICII RAPIDE - SECȚIUNE ÎMBUNĂTĂȚITĂ -->
<section class="quick-services">
    <div class="section-header">
        <h2>Unsere Leistungen</h2>
        <p class="subheading">Entdecken Sie unsere umfassenden Dachdecker-Dienstleistungen - von Neubau bis Nachhaltigkeit. Qualität und Zuverlässigkeit garantiert.</p>
    </div>
    <div class="services-container">
        <div class="services-grid">
            <div class="service-card">
                <i class="fas fa-home"></i>
                <h3>Neubau & Dachausbau</h3>
                <p>Massivdächer, Holzdächer, Gauben & mehr - planen & bauen wir fachgerecht.</p>
                <a href="services.php#neubau" class="card-link" aria-label="Mehr über Neubau & Dachausbau">Mehr erfahren <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="service-card">
                <i class="fas fa-tools"></i>
                <h3>Reparatur & Wartung</h3>
                <p>Von kleinen Reparaturen bis zur professionellen Dachwartung - wir halten Ihr Dach sicher.</p>
                <a href="services.php#reparatur" class="card-link" aria-label="Mehr über Reparatur & Wartung">Mehr erfahren <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="service-card">
                <i class="fas fa-thermometer-half"></i>
                <h3>Dämmung & Sanierung</h3>
                <p>Energie sparen mit moderner Dachdämmung - staatlich gefördert.</p>
                <a href="services.php#daemmung" class="card-link" aria-label="Mehr über Dämmung & Sanierung">Mehr erfahren <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="service-card">
                <i class="fas fa-solar-panel"></i>
                <h3>Photovoltaik & Gründächer</h3>
                <p>Nachhaltige Energie und ökologische Dachbegrünung aus einer Hand.</p>
                <a href="services.php#hochdruck" class="card-link" aria-label="Mehr über Photovoltaik & Gründächer">Mehr erfahren <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="service-card">
                <i class="fas fa-building"></i>
                <h3>Gauben & Dachausbau</h3>
                <p>Mehr Raum, Licht und Komfort – planen und bauen wir individuelle Gauben und Dachausbauten.</p>
                <a href="services.php#gauben" class="card-link" aria-label="Mehr über Gauben & Dachausbau">Mehr erfahren <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="btn-container">
        <a href="services.php" class="btn-secondary">Alle Dienstleistungen</a>
    </div>
</section>

<!-- VIDEO PROJECTS CAROUSEL -->
<section class="cinematic-carousel loading" role="region" aria-label="Projekte Video">
  <div class="swiper videoProjectsSwiper">
    <div class="swiper-wrapper">
      <!-- Slide 1 – Dachsanierung -->
      <div class="swiper-slide">
        <div class="video-container">
          <video class="bg-video" autoplay loop playsinline preload="metadata"
                 poster="<?= $assets_path ?>images/projects/project1-poster.webp">
            <source src="<?= $assets_path ?>video/projects/project1.mp4" type="video/mp4">
            <source src="<?= $assets_path ?>video/projects/project1.webm" type="video/webm">
            Ihr Browser unterstützt kein Video-Tag.
          </video>
          <div class="video-overlay"></div>
        </div>
        <div class="slide-overlay">
          <div class="slide-content">
            <div class="content-wrapper">
              <span class="project-tag">
                <i class="icon-roof"></i> Dachsanierung
              </span>
              <h2 class="project-title">Moderne Dachsanierung mit Trapez-Blech</h2>
              <p class="project-desc">
                <span class="location">Berlin Mitte</span>
                <span class="year">2024</span>
              </p>
              <div class="project-stats">
                <div class="stat"><span class="stat-number">170</span><span class="stat-label">m²</span></div>
                <div class="stat"><span class="stat-number">4</span><span class="stat-label">Tage</span></div>
              </div>
              <a href="contact.php" class="btn-explore">
                <span>Jetzt Kostenvoranschlag anfragen</span>
                <i class="icon-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- Slide 2 – Altbau-Dachausbau -->
      <div class="swiper-slide">
        <div class="video-container">
          <video class="bg-video" autoplay loop playsinline preload="metadata"
                 poster="<?= $assets_path ?>images/projects/project2-poster.webp">
            <source src="<?= $assets_path ?>video/projects/project2.mp4" type="video/mp4">
            <source src="<?= $assets_path ?>video/projects/project2.webm" type="video/webm">
            Ihr Browser unterstützt kein Video-Tag.
          </video>
          <div class="video-overlay"></div>
        </div>
        <div class="slide-overlay">
          <div class="slide-content">
            <div class="content-wrapper">
              <span class="project-tag">
                <i class="icon-roof"></i> Dachausbau
              </span>
              <h2 class="project-title">Altbau-Dachausbau</h2>
              <p class="project-desc">
                <span class="location">Berlin Charlottenburg</span>
                <span class="year">2023</span>
              </p>
              <div class="project-stats">
                <div class="stat"><span class="stat-number">210</span><span class="stat-label">m²</span></div>
                <div class="stat"><span class="stat-number">11</span><span class="stat-label">Tage</span></div>
              </div>
              <a href="contact.php" class="btn-explore">
                <span>Jetzt Kostenvoranschlag anfragen</span>
                <i class="icon-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- Slide 3 – Hochdruckreinigung / Nano-Versiegelung -->
      <div class="swiper-slide">
        <div class="video-container">
          <video class="bg-video" autoplay muted playsinline preload="metadata"
                 poster="<?= $assets_path ?>images/projects/project3-poster.webp">
            <source src="<?= $assets_path ?>video/projects/project3.mp4" type="video/mp4">
            <source src="<?= $assets_path ?>video/projects/project3.webm" type="video/webm">
            Ihr Browser unterstützt kein Video-Tag.
          </video>
          <div class="video-overlay"></div>
        </div>
        <div class="slide-overlay">
          <div class="slide-content">
            <div class="content-wrapper">
              <span class="project-tag">
                <i class="icon-solar-panel"></i> Hochdruckreinigung & Nano-Versiegelung
              </span>
              <h2 class="project-title">Hochdruckreiniger</h2>
              <p class="project-desc">
                <span class="location">Potsdam</span>
                <span class="year">2024</span>
              </p>
              <div class="project-stats">
                <div class="stat"><span class="stat-number">140</span><span class="stat-label">m²</span></div>
                <div class="stat"><span class="stat-number">3</span><span class="stat-label">Tage</span></div>
              </div>
              <a href="contact.php" class="btn-explore">
                <span>Jetzt Kostenvoranschlag anfragen</span>
                <i class="icon-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- Slide 4 – Einfamilienhaus Neubau -->
      <div class="swiper-slide">
        <div class="video-container">
          <video class="bg-video" autoplay loop playsinline preload="metadata"
                 poster="<?= $assets_path ?>images/projects/project4-poster.webp">
            <source src="<?= $assets_path ?>video/projects/project4.mp4" type="video/mp4">
            <source src="<?= $assets_path ?>video/projects/project4.webm" type="video/webm">
            Ihr Browser unterstützt kein Video-Tag.
          </video>
          <div class="video-overlay"></div>
        </div>
        <div class="slide-overlay">
          <div class="slide-content">
            <div class="content-wrapper">
              <span class="project-tag">
                <i class="icon-home"></i> Neubau
              </span>
              <h2 class="project-title">Einfamilienhaus Neubau</h2>
              <p class="project-desc">
                <span class="location">Potsdam</span>
                <span class="year">2024</span>
              </p>
              <div class="project-stats">
                <div class="stat"><span class="stat-number">280</span><span class="stat-label">m²</span></div>
                <div class="stat"><span class="stat-number">2</span><span class="stat-label">W</span></div>
              </div>
              <a href="contact.php" class="btn-explore">
                <span>Jetzt Kostenvoranschlag anfragen</span>
                <i class="icon-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Navigation -->
    <div class="carousel-navigation">
      <button class="swiper-button-prev" aria-label="Vorheriges Projekt"></button>
      <button class="swiper-button-next" aria-label="Nächstes Projekt"></button>
    </div>
    <!-- Pagination -->
    <div class="carousel-pagination">
      <div class="swiper-pagination"></div>
      <div class="pagination-progress">
        <div class="progress-bar"></div>
      </div>
    </div>
    <!-- Slide Counter -->
    <div class="slide-counter">
      <span class="current-slide">01</span><span class="divider">/</span><span class="total-slides">04</span>
    </div>
    <!-- Autoplay Progress -->
    <div class="swiper-autoplay-progress">
      <svg viewBox="0 0 48 48" aria-hidden="true">
        <circle cx="24" cy="24" r="20" stroke-dasharray="126" stroke-dashoffset="126"></circle>
      </svg>
      <span class="sr-only">Autoplay Fortschritt</span>
    </div>
  </div>
</section>

<!-- ABOUT SHORT SECTION -->
<section class="about-short">
    <div class="about-bg"></div>
    <div class="container">
        <div class="about-content">
            <h2>Unsere Philosophie – Qualität und Vertrauen</h2>
            <p>
                Wir legen großen Wert auf eine enge Zusammenarbeit mit unseren Kunden.
                Von der Planung bis zur Fertigstellung arbeiten wir transparent, zuverlässig und termingerecht.
            </p>
            <p>
                Unser Team aus erfahrenen <strong>Dachdeckern</strong>, <strong>Klempnern</strong> und <strong>Zimmerleuten</strong> sorgt dafür,
                dass jedes Projekt mit höchster Präzision und Liebe zum Detail umgesetzt wird.
            </p>
            <a href="about.php" class="btn-secondary">Lerne unser Team kennen</a>
        </div>
    </div>
</section>

<?php include(__DIR__ . '/includes/footer.php'); ?>
