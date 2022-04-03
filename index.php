<?php

require 'includes/init.php';

$conn = require 'includes/db.php';

// If $_GET['page'] exists & is not null 
// then use $_GET['page'] , otherwise use 1
$paginator = new Paginator($_GET['page'] ?? 1, 4, Article::getTotal($conn));

$articles = Article::getPage($conn, $paginator->limit, $paginator->offset);

?>

<?php require 'includes/header.php'; ?>

<?php if (empty($articles)): ?>
    <p>No articles found.</p>
<?php else: ?>

    <ul>
        <?php foreach ($articles as $article): ?>
            <li> <h3><a href="article.php?id=<?= $article['id']; ?>"><?= htmlspecialchars($article['title']); ?></a></h3> </li>
            <li> <p><?= htmlspecialchars($article['content']); ?></p> </li>
            <li> <p><?= htmlspecialchars($article['published_at']); ?></p></li>
        <?php endforeach; ?>
    </ul>
    
    <?php require 'includes/pagination.php'; ?>

<?php endif; ?>

<?php require 'includes/footer.php'; ?>
