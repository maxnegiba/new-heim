<?php
// Production-safe shared header.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'dachdeckerberlin24.de';
$base_url = $protocol . $host . '/';
$assets_path = $base_url . 'assets/';
$current_page = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$is_home = ($current_page === 'index.php' || $request_uri === '/');

if (!isset($page_title)) {
    $page_title = 'MB Bau Dienstleistungen | Dachdecker Berlin';
}
if (!isset($page_description)) {
    $page_description = 'Professionelle Dacharbeiten, Fassadenarbeiten und Zaunbau in Berlin und Umgebung. Kostenlose Beratung und individuelles Angebot.';
}

// Buffer the rendered page so legacy company/phone references in older content
// are consistently replaced by the current MB Bau branding.
ob_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($base_url . ltrim($request_uri, '/'), ENT_QUOTES, 'UTF-8') ?>">

    <?php if ($is_home): ?>
        <link rel="preload" href="<?= $assets_path ?>video/hero-video.mp4" as="video" type="video/mp4">
    <?php endif; ?>
    <link rel="preload" href="<?= $assets_path ?>img/logo-mb-bau.svg" as="image" type="image/svg+xml">
    <link rel="preload" href="<?= $assets_path ?>css/bundle.min.css" as="style">
    <link rel="stylesheet" href="<?= $assets_path ?>css/bundle.min.css">

    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.min.css" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <style>
        html { overflow-y: scroll; }
        .header .logo { display:flex; align-items:center; height:100%; }
        .header .logo .logo-text { width:82px; height:82px; max-height:82px; object-fit:contain; border-radius:50%; }
        @media (max-width:768px) { .header .logo .logo-text { width:62px; height:62px; max-height:62px; } }
        @media (max-width:600px) { .header .logo .logo-text { width:56px; height:56px; max-height:56px; } }
    </style>

    <script src="<?= $assets_path ?>js/main.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer crossorigin></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer crossorigin></script>
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.5/dist/js/lightbox.min.js" defer crossorigin></script>
</head>
<body>
<header class="header">
    <div class="container">
        <a href="<?= $base_url ?>" class="logo" aria-label="MB Bau Dienstleistungen Startseite">
            <img src="<?= $assets_path ?>img/logo-mb-bau.svg"
                 alt="MB Bau Dienstleistungen"
                 class="logo-text"
                 width="82"
                 height="82">
        </a>

        <nav class="nav-desktop" aria-label="Hauptnavigation">
            <ul>
                <li><a href="<?= $base_url ?>" class="<?= $is_home ? 'active' : '' ?>">Heim</a></li>
                <li><a href="<?= $base_url ?>about.php" class="<?= $current_page === 'about.php' ? 'active' : '' ?>">Über uns</a></li>
                <li><a href="<?= $base_url ?>services.php" class="<?= $current_page === 'services.php' ? 'active' : '' ?>">Dienstleistungen</a></li>
                <li><a href="<?= $base_url ?>projects.php" class="<?= $current_page === 'projects.php' ? 'active' : '' ?>">Unsere Projekte</a></li>
                <li><a href="<?= $base_url ?>blog/" class="<?= strpos($request_uri, '/blog') === 0 ? 'active' : '' ?>">Blog</a></li>
                <li><a href="<?= $base_url ?>contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?> cta-button">Kontakt</a></li>
            </ul>
        </nav>

        <button class="hamburger mobile-nav" aria-label="Menü öffnen" aria-controls="nav-mobile" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="nav-mobile" id="nav-mobile" aria-label="Mobile Hauptnavigation">
            <ul>
                <li><a href="<?= $base_url ?>" class="<?= $is_home ? 'active' : '' ?>">Heim</a></li>
                <li><a href="<?= $base_url ?>about.php" class="<?= $current_page === 'about.php' ? 'active' : '' ?>">Über uns</a></li>
                <li><a href="<?= $base_url ?>services.php" class="<?= $current_page === 'services.php' ? 'active' : '' ?>">Dienstleistungen</a></li>
                <li><a href="<?= $base_url ?>projects.php" class="<?= $current_page === 'projects.php' ? 'active' : '' ?>">Unsere Projekte</a></li>
                <li><a href="<?= $base_url ?>blog/" class="<?= strpos($request_uri, '/blog') === 0 ? 'active' : '' ?>">Blog</a></li>
                <li><a href="<?= $base_url ?>contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?>">Kontakt</a></li>
            </ul>
        </nav>
    </div>
</header>
<div class="mobile-overlay"></div>
<main>
