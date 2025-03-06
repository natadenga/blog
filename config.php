<?php
// Налаштування підключення до бази даних
$host = '';     // Хост, зазвичай localhost
$dbname = ''; // Ім'я бази даних
$username = ''; // Ім'я користувача для підключення
$password = ''; // Пароль до бази даних

// Створення з'єднання з базою даних
try {
    $db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    // Встановлюємо режим помилок на виключення
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Встановлюємо кодування для з'єднання
    $db->exec('SET NAMES utf8');
} catch (PDOException $e) {
    // У разі помилки виводимо повідомлення
    echo 'Помилка підключення до бази даних: ' . $e->getMessage();
    exit;
}

// Інші параметри конфігурації (якщо потрібно)
define('BASE_URL', ''); // якщо сайт у папці, то вкажіть назву за зразком /blog
