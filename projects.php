<?php
include(__DIR__ . '/includes/header.php');

// Funcție pentru scanarea folderului și generarea array-ului de proiecte
function getProjectsFromFolder($folder_path = 'assets/img/projects/') {
    $projects = [];
    
    // Verifică dacă folderul există
    if (!is_dir($folder_path)) {
        return $projects;
    }
    
    // Scanează folderul pentru fișiere .jpg (versiunile normale)
    $files = glob($folder_path . '*.jpg');
    
    foreach ($files as $file) {
        $filename = basename($file, '.jpg');
        
        // Sare peste fișierele @2x
        if (strpos($filename, '@2x') !== false) {
            continue;
        }
        
        // Construiește căile pentru toate variantele
        $base_path = $folder_path . $filename;
        $web_path = '/' . $folder_path . $filename;
        
        // Verifică dacă toate variantele există
        $project = [
            'title' => ucfirst(str_replace(['proj', '_', '-'], ['Projekt ', ' ', ' '], $filename)),
            'src' => $web_path . '.jpg',
            'src2x' => $web_path . '@2x.jpg',
            'webp' => $web_path . '.webp',
            'webp2x' => $web_path . '@2x.webp'
        ];
        
        // Verifică dacă fișierele există fizic
        if (file_exists($base_path . '.jpg') && 
            file_exists($base_path . '@2x.jpg') && 
            file_exists($base_path . '.webp') && 
            file_exists($base_path . '@2x.webp')) {
            $projects[] = $project;
        }
    }
    
    // Sortează proiectele după nume
    usort($projects, function($a, $b) {
        return strcmp($a['title'], $b['title']);
    });
    
    return $projects;
}

$projects = getProjectsFromFolder();
$total = count($projects);
$assets_path = '/assets/';
?>

<section class="projects-hero">
    <div class="container">
        <h1>Unsere Projekte</h1>
        <p>Überzeugen Sie sich von unserer Handwerkskunst – hier eine Auswahl abgeschlossener Arbeiten.</p>
    </div>
</section>

<section class="projects-grid">
    <div class="container">
        <?php if (empty($projects)): ?>
            <div class="no-projects">
                <p>Momentan nu sunt proiecte disponibile.</p>
            </div>
        <?php else: ?>
            <div id="gallery" data-total="<?= $total ?>">
                <?php foreach (array_slice($projects, 0, 12) as $i => $p): ?>
                    <div class="project-card" data-index="<?= $i ?>">
                        <picture>
                            <source type="image/webp"
                                    srcset="<?= htmlspecialchars($p['webp']) ?> 1x, <?= htmlspecialchars($p['webp2x']) ?> 2x">
                            <img src="<?= htmlspecialchars($p['src']) ?>"
                                 srcset="<?= htmlspecialchars($p['src2x']) ?> 2x"
                                 loading="lazy"
                                 data-full="<?= htmlspecialchars($p['src2x']) ?>"
                                 alt="<?= htmlspecialchars($p['title']) ?>">
                        </picture>
                        <div class="overlay"><span><?= htmlspecialchars($p['title']) ?></span></div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($total > 12): ?>
            <div id="loader" style="text-align:center; margin:40px 0; display:none;">
                <p>Se încarcă… <span id="loaded">12</span>/<?= $total ?></p>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="projectModal" class="modal">
    <span class="close">&times;</span>
    <button class="arrow left" aria-label="Previous image">&larr;</button>
    <img id="modalImg" alt="">
    <button class="arrow right" aria-label="Next image">&rarr;</button>
    <div id="modalCaption"></div>
</div>

<script>
// Pasează datele către JavaScript
window.allProjects = <?= json_encode($projects, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
window.totalProjects = <?= $total ?>;
</script>
<script type="module" src="<?= $assets_path ?>js/projects.js"></script>

<?php include(__DIR__ . '/includes/footer.php'); ?>