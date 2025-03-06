<!-- views/admin.php -->
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адмін-панель</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Адмін-панель</h2>
        <h3>Список постів</h3>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li><a href="/post/<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
