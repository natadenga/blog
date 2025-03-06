<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Головна сторінка</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css"/>
    </head>
    <body>
        <?php if ($_SESSION["user_role"] === "admin") include 'nav/navbar_admin.php';
        else include 'nav/navbar.php'; ?>
        <div class="container mt-5">
            <!-- Hero -->
            <div class="p-5 text-center bg-image rounded-3 hero" style="
                 background-image: url('https://mdbcdn.b-cdn.net/img/new/slides/041.webp');
                 height: 200px;
                 ">
                <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-white">
                            <h1 class="mb-3">МІЙ БЛОГ</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Hero -->

            <!-- Якщо є повідомлення -->
            <?php
            session_start(); // Починаємо сесію
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']); // Очищаємо повідомлення після виведення
            }
            ?>

            <!-- Якщо постів немає -->
<?php if (empty($posts)): ?>
                <div class="alert alert-warning" role="alert">
                    Немає постів.
                </div>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <p>Ви можете <a href="/blog/register">зареєструватися</a> або <a href="/blog/login">увійти</a>, щоб додавати пости.</p>
                <?php endif; ?>
<?php else: ?>
                
                <div class="list-group">
                        <?php foreach ($posts as $post): ?>
                        <a href="<?= BASE_URL ?>/post/<?php echo $post['id']; ?>" class="list-group-item list-group-item-action">
                        <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                <?php endforeach; ?>
                </div>
<?php endif; ?>

            <!-- Кнопка для створення поста та посилання на авторизацію -->
            <div class="mt-4">
<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === "admin"): ?>
                    <a href="<?= BASE_URL ?>/create" class="btn btn-success">Створити пост</a>
                <?php elseif (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/create" class="btn btn-primary">Створити пост</a>
                <?php else: ?>
                    <p>Щоб створити пост, <a href="<?= $baseUrl ?>/register">зареєструйтесь</a> або <a href="/blog/login">увійдіть</a>.</p>
<?php endif; ?>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
