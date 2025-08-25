<?php
require 'db.php';
$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ?");
$stmt->execute([$slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) { http_response_code(404); echo 'Post nicht gefunden'; exit; }

include 'header.php';
?>

<article class="blog-post">
  <h1><?= htmlspecialchars($post['title']) ?></h1>
  <time><?= date('d.m.Y', strtotime($post['created_at'])) ?></time>
  <div class="content"><?= nl2br(htmlspecialchars($post['body'])) ?></div>
</article>

<!-- Utterances comments -->
<script src="https://utteranc.es/client.js"
        repo="YOUR_GITHUB_USERNAME/hausmeistermichael-gmbh"
        issue-term="pathname"
        theme="github-light"
        crossorigin="anonymous" async>
</script>

<?php include 'footer.php'; ?>