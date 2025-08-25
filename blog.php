<?php
require 'db.php';
$stmt = $pdo->query("SELECT id, title, slug, created_at
                     FROM blog_posts
                     ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
include '/includes/header.php';
?>

<section class="blog-list">
  <h1>Aktuelle Beiträge</h1>

  <?php foreach ($posts as $post): ?>
    <article>
      <h2><a href="blog/<?= $post['slug'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
      <time><?= date('d.m.Y', strtotime($post['created_at'])) ?></time>
    </article>
  <?php endforeach; ?>
</section>

<?php include '/includes/header.php'; ?>