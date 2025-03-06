<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = $_POST['db_host'];
    $dbname = $_POST['db_name'];
    $username = $_POST['db_user'];
    $password = $_POST['db_pass'];
    $base_url = rtrim($_POST['base_url'], '/');

    try {
        // Підключення до БД
        $db = new PDO("mysql:host=$host", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Створення бази даних
        $db->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $db->exec("USE `$dbname`");
        
        // Імпорт SQL файлу
        $sql = file_get_contents(__DIR__ . '/blog.sql');
        $db->exec($sql);
        
        // Запис конфігураційного файлу з коментарями
        $config_content = "<?php\n"
            . "// Налаштування підключення до бази даних\n"
            . "\$host = '$host';     // Хост, зазвичай localhost\n"
            . "\$dbname = '$dbname'; // Ім'я бази даних\n"
            . "\$username = '$username'; // Ім'я користувача для підключення\n"
            . "\$password = '$password'; // Пароль до бази даних\n\n"
            . "// Створення з'єднання з базою даних\n"
            . "try {\n"
            . "    \$db = new PDO('mysql:host=' . \$host . ';dbname=' . \$dbname, \$username, \$password);\n"
            . "    // Встановлюємо режим помилок на виключення\n"
            . "    \$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n"
            . "    // Встановлюємо кодування для з'єднання\n"
            . "    \$db->exec('SET NAMES utf8');\n"
            . "} catch (PDOException \$e) {\n"
            . "    // У разі помилки виводимо повідомлення\n"
            . "    echo 'Помилка підключення до бази даних: ' . \$e->getMessage();\n"
            . "    exit;\n"
            . "}\n\n"
            . "// Інші параметри конфігурації (якщо потрібно)\n"
            . "define('BASE_URL', '$base_url'); // якщо сайт у папці, то вкажіть назву за зразком /blog\n";

        file_put_contents(__DIR__ .'/../config.php', $config_content);

        // Видалення install.php
        //unlink(__FILE__);
        $_SESSION['install_success'] = "1";
        $_SESSION['message'] = "Видаліть чи перемістіть файл install.php";
        // Перенаправлення на головну сторінку
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Помилка: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Інсталятор Блогу</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Інсталяція Блогу</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"> <?= $error ?> </div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Хост</label>
            <input type="text" name="db_host" class="form-control" value="localhost" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ім'я бази даних</label>
            <input type="text" name="db_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Користувач</label>
            <input type="text" name="db_user" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Пароль</label>
            <input type="password" name="db_pass" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">BASE_URL (вкажіть назву папки якщо сайт не в кореневому каталозі Приклад /blog)</label>
            <input type="text" name="base_url" class="form-control" placeholder="" >
        </div>
        <button type="submit" class="btn btn-primary">Встановити</button>
    </form>
</body>
</html>
