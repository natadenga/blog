<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
#<?php
// index.php

session_start();
//ПЕРЕВІРКА ІНСТАЛЯТОРА
if (file_exists(__DIR__.'/install/install.php') && !isset($_SESSION["install_success"])){
    include_once __DIR__.'/install/install.php';
    exit();
}
//ПЕРЕВІРКА ІНСТАЛЯТОРА
unset($_SESSION['install_success']);
require_once 'config.php';
require_once 'models/User.php';
require_once 'models/Post.php';
require_once 'models/Comment.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/PostController.php';
require_once 'controllers/CommentController.php';
require_once 'controllers/AdminController.php'; // Підключаємо контролер для адмін-панелі



// Створюємо екземпляри моделей
$userModel = new User($db);
$postModel = new Post($db);
$commentModel = new Comment($db);

// Створюємо контролери
$authController = new AuthController($userModel);
$postController = new PostController($postModel,$userModel,$commentModel);
$commentController = new CommentController($commentModel,$userModel);
$adminController = new AdminController($postModel, $userModel); // Створюємо екземпляр для адмін-контролера



// Основні маршрути
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//$uri = rtrim($uri, '/');

// Якщо головна сторінка
if ($uri === BASE_URL . '/' || $uri === BASE_URL . '/index.php') {
    // Перевірка чи є пости
    $posts = $postController->index(); // Отримуємо пости
    include './views/home.php'; // Підключаємо окремий файл для головної сторінки
}

// Маршрути для авторизації
elseif ($uri === BASE_URL . '/login') {
    $authController->login();
} elseif ($uri === BASE_URL . '/register') {
    $authController->register();
}elseif ($uri === BASE_URL . '/forgot_password') {
    $authController->sendResetPasswordEmail();
} elseif ($uri === BASE_URL . '/reset_password') {
    $authController->resetPassword();
}elseif ($uri === BASE_URL . '/logout') {
    $authController->logout();
} elseif (preg_match('/^' . preg_quote(BASE_URL, '/') . '\/profile\/(\d+)$/', $uri, $matches)) {
    if (isset($_SESSION['user_id'])){
    $authController->editProfile();
    }else{
        include './views/home.php';
    }

// Маршрути для постів
} elseif ($uri === BASE_URL . '/create') {
    $postController->create();
} elseif (preg_match('/^' . preg_quote(BASE_URL, '/') . '\/post\/(\d+)$/', $uri, $matches)) {
    $postController->show($matches[1]);
} elseif (preg_match('/^' . preg_quote(BASE_URL, '/') . '\/post\/(\d+)\/edit$/', $uri, $matches)) {
    $postController->edit($matches[1]);

// Маршрути для коментарів
} elseif (preg_match('/^' . preg_quote(BASE_URL, '/') . '\/post\/(\d+)\/comment$/', $uri, $matches)) {
    $commentController->addComment($matches[1]);


}// Видалення поста
elseif (preg_match('/^' . preg_quote(BASE_URL, '/') . '\/post\/(\d+)\/delete$/', $uri, $matches)) {
    if (isset($_SESSION['user_role'])) {
        $postId = $matches[1];
        $adminController->deletePost($postId);
    } else {
        header('Location: ' . BASE_URL . '/');
    }

// Адмін-панель
} elseif (preg_match('#^' . preg_quote(BASE_URL, '#') . '/admin/posts#', $uri)) {
    if ($_SESSION['user_role'] === 'admin') {
        // Отримуємо пости та користувачів для адмін-панелі
        $posts = $adminController->getPosts();
        include './views/admin.php'; // Підключаємо адмін-панель
    } else {
        header('Location: ' . BASE_URL . '/'); // Якщо не адміністратор, перенаправляємо на головну
        exit();
}
}elseif (preg_match('#^' . preg_quote(BASE_URL, '#') . '/admin/users#', $uri)) {
    if ($_SESSION['user_role'] === 'admin') {
        // Отримуємо пости та користувачів для адмін-панелі
       
        $users = $adminController->getUsers();
        include './views/admin.php'; // Підключаємо адмін-панель
    } else {
        header('Location: ' . BASE_URL . '/'); // Якщо не адміністратор, перенаправляємо на головну
        exit();
}
}

// Видалення користувача
 elseif (preg_match('/^' . preg_quote(BASE_URL, '/') . '\/admin\/user\/(\d+)\/delete$/', $uri, $matches)) {
    if ($_SESSION['user_role'] === 'admin') {
        $userId = $matches[1];
        $adminController->deleteUser($userId);
    } else {
        header('Location: ' . BASE_URL . '/');
    }

}
else {
    echo "404 - Сторінка не знайдена"; // Якщо сторінку не знайдено
}
