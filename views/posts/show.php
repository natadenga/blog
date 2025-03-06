<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <!-- Bootstrap CSS з CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=BASE_URL?>/assets/css/style.css"/>
</head>
<body>
    <?php if ($_SESSION["user_role"] === "admin") include __DIR__ .'/../nav/navbar_admin.php'; else  include __DIR__ .'/../nav/navbar.php';?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                <p class="text-muted">Опубліковано: <?php echo date('d.m.Y H:i', strtotime($post['created_at'])); ?></p>
                <hr>
                <div class="card-text">
                    <?php echo $post['content']; // Вивід контенту поста ?>
                </div>
                
            </div>
        </div>

        <!-- Секція коментарів -->
        <div class="mt-5">
            <h3>Коментарі</h3>
            <?php if (!empty($comments)): ?>
                <ul class="list-group">
                    <?php foreach ($comments as $comment): ?>
                        <li class="list-group-item">
                            <p><strong><?php echo htmlspecialchars($comment['first_name']." ".$comment['last_name']); ?>:</strong><small class="text-muted"><?php echo date('d.m.Y H:i', strtotime($comment['created_at'])); ?></small></p>
                            <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                            
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Коментарів поки що немає.</p>
            <?php endif; ?>
        </div>

        <!-- Форма для додавання коментаря -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="mt-4">
            <h4>Додати коментар</h4>
            <form action="<?=BASE_URL?>/post/<?php echo $post['id']; ?>/comment" method="POST">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <div class="mb-3">
                    <label for="comment" class="form-label">Коментар</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Додати коментар</button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS з CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
