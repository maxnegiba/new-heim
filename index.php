<?php
// Setări SEO pentru pagina principală
$page_title = "Dachdecker Berlin Brandenburg | Klempner & Zimmermann | Der Hausmeister Michael GmbH";
$page_description = "Professionelle Dachdecker, Klempner & Zimmermann in Berlin & Brandenburg. Über 20 Jahre Erfahrung. Kostenlose Beratung & Angebot!";

include(__DIR__ . '/includes/header.php');
// include(__DIR__ . '/includes/contact-float.php'); // Dacă este folosit, poate fi inclus mai jos
?>

<!-- CSS Critic pentru Homepage -->


<!-- HERO SECTION -->
<section class="hero-section">
    <video class="hero-video"
           autoplay
           muted
           loop
           playsinline
           preload="metadata"
           >
       
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
                 >
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
                 >
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
                 >
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
                 >
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
