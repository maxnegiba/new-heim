<?php
$host = $_SERVER['HTTP_HOST'] ?? 'dachdeckerberlin24.de';
$current_page = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$request_path = parse_url($request_uri, PHP_URL_PATH) ?: '/';
$is_blog = str_starts_with($request_path, '/blog');
$is_home = !$is_blog && ($current_page === 'index.php' || $request_path === '/');
$base_url = '/';
$assets_path = '/assets/';

if (!isset($page_title)) {
    $page_title = 'MB Bau Dienstleistungen | Dacharbeiten in Berlin';
}
if (!isset($page_description)) {
    $page_description = 'MB Bau Dienstleistungen für Dacharbeiten, Fassadenarbeiten und Zaunbau in Berlin und Umgebung. Direkte Beratung und individuelles Angebot.';
}

$canonical_url = 'https://dachdeckerberlin24.de' . $request_path;
$page_class = $is_blog ? 'page-blog' : ($is_home ? 'page-home' : 'page-' . preg_replace('/[^a-z0-9-]+/i', '-', pathinfo($current_page, PATHINFO_FILENAME)));
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#d32f2f">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8') ?>">

    <?php if ($is_home): ?>
        <link rel="preload" href="<?= $assets_path ?>video/hero-video.mp4" as="video" type="video/mp4">
    <?php endif; ?>
    <link rel="preload" href="<?= $assets_path ?>img/logo-mb-bau.svg" as="image" type="image/svg+xml">
    <link rel="stylesheet" href="<?= $assets_path ?>css/bundle.min.css">
    <link rel="stylesheet" href="<?= $assets_path ?>css/polish.css?v=20260825">

    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.min.css" crossorigin>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer crossorigin></script>
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.5/dist/js/lightbox.min.js" defer crossorigin></script>
    <script src="<?= $assets_path ?>js/main.js?v=20260825" defer></script>
</head>
<body class="<?= htmlspecialchars($page_class, ENT_QUOTES, 'UTF-8') ?>">
<a class="skip-link" href="#main-content">Zum Inhalt springen</a>
<header class="header">
    <div class="container">
        <a href="<?= $base_url ?>" class="logo" aria-label="MB Bau Dienstleistungen Startseite">
            <img src="<?= $assets_path ?>img/logo-mb-bau.svg"
                 alt="MB Bau Dienstleistungen"
                 class="logo-text"
                 width="76"
                 height="76">
        </a>

        <nav class="nav-desktop" aria-label="Hauptnavigation">
            <ul>
                <li><a href="/" class="<?= $is_home ? 'active' : '' ?>">Start</a></li>
                <li><a href="/about.php" class="<?= $current_page === 'about.php' ? 'active' : '' ?>">Über uns</a></li>
                <li><a href="/services.php" class="<?= $current_page === 'services.php' ? 'active' : '' ?>">Leistungen</a></li>
                <li><a href="/projects.php" class="<?= $current_page === 'projects.php' ? 'active' : '' ?>">Projekte</a></li>
                <li><a href="/blog/" class="<?= $is_blog ? 'active' : '' ?>">Blog</a></li>
                <li><a href="/contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?> cta-button">Kontakt</a></li>
            </ul>
        </nav>

        <button class="hamburger mobile-nav" type="button" aria-label="Menü öffnen" aria-controls="nav-mobile" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="nav-mobile" id="nav-mobile" aria-label="Mobile Hauptnavigation">
            <ul>
                <li><a href="/" class="<?= $is_home ? 'active' : '' ?>">Start</a></li>
                <li><a href="/about.php" class="<?= $current_page === 'about.php' ? 'active' : '' ?>">Über uns</a></li>
                <li><a href="/services.php" class="<?= $current_page === 'services.php' ? 'active' : '' ?>">Leistungen</a></li>
                <li><a href="/projects.php" class="<?= $current_page === 'projects.php' ? 'active' : '' ?>">Projekte</a></li>
                <li><a href="/blog/" class="<?= $is_blog ? 'active' : '' ?>">Blog</a></li>
                <li><a href="/contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?>">Kontakt</a></li>
            </ul>
        </nav>
    </div>
</header>
<div class="mobile-overlay" aria-hidden="true"></div>
<main id="main-content">
