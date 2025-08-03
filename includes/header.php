<?php
// Depanare - activare afișare erori (dezactivează în producție)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Determină URL-ul absolut al root-ului site-ului
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . $host . '/';
$assets_path = $base_url . 'assets/';

// Determină pagina curentă pentru meniul activ și logica CSS
$current_page = basename($_SERVER['PHP_SELF']);
$request_uri = $_SERVER['REQUEST_URI'];
$is_home = ($current_page === 'index.php' || $request_uri === '/' || $request_uri === '/');

// Asigură că $page_title și $page_description sunt definite pentru fiecare pagină
if (!isset($page_title)) {
    $page_title = 'MeisterDach - Dachdecker Meisterbetrieb';
}
if (!isset($page_description)) {
    $page_description = 'Professionelle Dachdecker, Klempner & Zimmermann in Berlin & Brandenburg. Über 20 Jahre Erfahrung. Kostenlose Beratung & Angebot!';
}

// Funcție pentru a determina toate fișierele CSS necesare
function getRequiredCSSFiles($current_page, $assets_path, $is_home) {
    $css_files = [];
    
    // Întotdeauna încarcă main.css pentru toate paginile
    $css_files[] = $assets_path . 'css/main.css';
    
    // Pentru paginile non-homepage, adaugă și CSS-ul specific
    if (!$is_home) {
        $page_specific_css = [
            'contact.php' => $assets_path . 'css/contact.css',
            'services.php' => $assets_path . 'css/services.css',
            'projects.php' => $assets_path . 'css/projects.css',
            'about.php' => $assets_path . 'css/about.css'
        ];
        
        if (isset($page_specific_css[$current_page])) {
            $css_files[] = $page_specific_css[$current_page];
        }
    }
    
    return $css_files;
}

// Obține toate fișierele CSS necesare
$required_css_files = getRequiredCSSFiles($current_page, $assets_path, $is_home);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8') ?>">
    
    <!-- Preload resurse critice pentru afișarea inițială -->
    <?php if ($is_home): ?>
        <link rel="preload" href="<?= $assets_path ?>video/hero-video.mp4" as="video" type="video/mp4">
    <?php endif; ?>
    <link rel="preload" href="<?= $assets_path ?>img/logo-text.jpg" as="image">
    
    <!-- CSS Critic Inline pentru Header - Asigură afișarea corectă fără FOUC -->
    <style>
        /* === CRITICAL HEADER STYLES - INLINE === */
        .header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 249, 250, 0.95));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border-bottom: 1px solid rgba(211, 47, 47, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1010;
            height: 100px;
            padding: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .header .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            padding: 0 32px;
            position: relative;
        }
        
        /* === DESKTOP NAVIGATION - CRITICAL PARTS === */
        .nav-desktop {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 2rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border-radius: 50px;
            padding: 8px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            z-index: 1005;
        }
        
        .nav-desktop ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 8px;
            align-items: center;
        }
        
        .nav-desktop li {
            position: relative;
        }
        
        .nav-desktop a {
            text-decoration: none;
            color: #2c3e50;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 20px;
            border-radius: 25px;
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
            display: block;
        }
        
        .nav-desktop a.active {
            color: #fff;
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
            transform: translateY(-1px);
        }
        
        /* === MOBILE NAVIGATION - CRITICAL PARTS (hidden by default) === */
        .mobile-nav,
        .hamburger,
        .nav-mobile {
            display: none;
        }
        
        /* === MEDIA QUERY pentru mobil - CRITICAL === */
        @media (max-width: 991.98px) {
            .nav-desktop,
            .header .cta-button {
                display: none;
            }
            
            .mobile-nav,
            .hamburger,
            .nav-mobile {
                display: block;
            }
            
            .hamburger {
                position: relative;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(15px);
                border: 2px solid rgba(211, 47, 47, 0.1);
                border-radius: 12px;
                padding: 10px;
                width: 44px;
                height: 44px;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            
            .hamburger span {
                position: absolute;
                left: 10px;
                height: 2px;
                width: 24px;
                background: linear-gradient(90deg, #d32f2f, #b71c1c);
                border-radius: 2px;
                transition: all 0.3s ease;
            }
            
            .hamburger span:nth-child(1) { top: 12px; }
            .hamburger span:nth-child(2) { top: 20px; }
            .hamburger span:nth-child(3) { top: 28px; }
            
            .nav-mobile {
                position: fixed;
                top: 100px;
                left: -100%;
                width: 85%;
                max-width: 300px;
                height: calc(100vh - 100px);
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(248, 249, 250, 0.98));
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                box-shadow: 4px 0 30px rgba(0, 0, 0, 0.15);
                transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1008;
                overflow-y: auto;
                border-right: 1px solid rgba(211, 47, 47, 0.1);
            }
            
            .nav-mobile.active {
                left: 0;
            }
        }
        
        @media (max-width: 768px) {
            .header { height: 80px; }
            .nav-mobile { top: 80px; height: calc(100vh - 80px); }
            .header.scrolled { height: 70px; }
            .logo-main, .logo-text { max-height: 40px; }
            .header.scrolled .logo-main,
            .header.scrolled .logo-text { max-height: 35px; }
        }
        
        /* === PRINT - CRITICAL === */
        @media print {
            .header { position: static; box-shadow: none; background: white; }
            .nav-desktop a, .nav-mobile a { color: black !important; }
        }
    </style>
    
    <!-- Încărcare CSS pentru toate paginile -->
    <?php foreach ($required_css_files as $css_file): ?>
        <link rel="preload" href="<?= $css_file ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="<?= $css_file ?>"></noscript>
    <?php endforeach; ?>
    <!-- Preload critical fonts -->
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin>
    <!-- External libraries - încărcare asincronă -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'" crossorigin>
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.min.css"></noscript>


    <link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'" crossorigin>
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"></noscript>
    
    <!-- JavaScript - încărcare deferred -->
    <script src="<?= $assets_path ?>js/main.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer crossorigin></script>
   
<script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.5/dist/js/lightbox.min.js" defer crossorigin></script>

    
</head>
<body>
    <!-- Depanare - comentariu HTML cu informații utile (șterge în producție) -->
    <!-- 
        Pagină curentă: <?= $current_page ?>
        URL de bază: <?= $base_url ?>
        Cale resurse: <?= $assets_path ?>
        Este homepage: <?= $is_home ? 'Da' : 'Nu' ?>
        Fișiere CSS încărcate: <?= implode(', ', $required_css_files) ?>
    -->
    
    <header class="header">
        <div class="container">
            <!-- Logo -->
            <a href="<?= $base_url ?>" class="logo">
                <img src="<?= $assets_path ?>img/logo-text.jpg" 
     alt="Dachdecker Meisterbetrieb Der Hausmeister Michael GmbH" 
     class="logo-text" 
     width="200" 
     height="50">
            </a>
            
            <!-- Meniu Desktop -->
            <nav class="nav-desktop" aria-label="Hauptnavigation">
                <ul>
                    <li><a href="<?= $base_url ?>" class="<?= $is_home ? 'active' : '' ?>">Heim</a></li>
                    <li><a href="<?= $base_url ?>about.php" class="<?= $current_page === 'about.php' ? 'active' : '' ?>">Über uns</a></li>
                    <li><a href="<?= $base_url ?>services.php" class="<?= $current_page === 'services.php' ? 'active' : '' ?>">Dienstleistungen</a></li>
                    <li><a href="<?= $base_url ?>projects.php" class="<?= $current_page === 'projects.php' ? 'active' : '' ?>">Unsere Projekte</a></li>
                    <li><a href="<?= $base_url ?>contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?> cta-button">Kontakt</a></li>
                </ul>
            </nav>
            
            <!-- Meniu Mobil -->
            <button class="hamburger mobile-nav" aria-label="Deschide meniul" aria-controls="nav-mobile" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <nav class="nav-mobile" id="nav-mobile">
                <ul>
                    <li><a href="<?= $base_url ?>" class="<?= $is_home ? 'active' : '' ?>">Heim</a></li>
                    <li><a href="<?= $base_url ?>about.php" class="<?= $current_page === 'about.php' ? 'active' : '' ?>">Über uns</a></li>
                    <li><a href="<?= $base_url ?>services.php" class="<?= $current_page === 'services.php' ? 'active' : '' ?>">Dienstleistungen</a></li>
                    <li><a href="<?= $base_url ?>projects.php" class="<?= $current_page === 'projects.php' ? 'active' : '' ?>">Unsere Projekte</a></li>
                    <li><a href="<?= $base_url ?>contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?>">Kontakt</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Mobile overlay -->
    <div class="mobile-overlay"></div>
    
    <main>