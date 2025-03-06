<!-- views/admin.php -->
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адмін-панель</title>
    <!-- Bootstrap CSS з CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=BASE_URL?>/assets/css/style.css"/>
</head>
<body>
    <?php include 'nav/navbar_admin.php'; ?>
    <div class="container mt-5">
        <h2>Адмін-панель</h2>
        <?php if (isset($posts)): ?>
        <h3>Список постів</h3>
        
        <!-- Список постів -->
        <ul class="list-group">
            <?php foreach ($posts as $post): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <a href="<?=BASE_URL?>/post/<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a>
                        <br>
                        <small>Автор: <?php echo $post['first_name'] . ' ' . $post['last_name']; ?></small>
                        <br>
                        <small>Кількість коментарів: <?php echo $post['comments_count']; ?></small>
                    </div>

                    <div>
                        <!-- Кнопки редагування та видалення -->
                        <a href="<?=BASE_URL?>/post/<?php echo $post['id']; ?>/edit" class="btn btn-warning btn-sm">Редагувати</a>
                        <form action="<?=BASE_URL?>/post/delete/<?php echo $post['id']; ?>" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ви впевнені, що хочете видалити цей пост?')">Видалити</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <?php if (isset($users)): ?>
        <h3 class="mt-5">Користувачі</h3>
        
        <ul class="list-group">
            <?php foreach ($users as $user): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <?php echo $user['first_name'] . ' ' . $user['last_name']; ?> (<?php echo $user['email']; ?>)
                    </div>
                    <div>
                        <!-- Кнопки для редагування користувача чи видалення -->
                        <a href="<?=BASE_URL?>/profile/<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Редагувати</a>
                        <form action="<?=BASE_URL?>/admin/delete-user/<?php echo $user['id']; ?>" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ви впевнені, що хочете видалити цього користувача?')">Видалити</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS з CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
