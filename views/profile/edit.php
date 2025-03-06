<!-- views/profile/edit.php -->
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати профіль</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=BASE_URL?>/assets/css/style.css"/>
</head>
<body>
    <?php if ($_SESSION["user_role"] === "admin") include __DIR__ .'/../nav/navbar_admin.php'; else  include __DIR__ .'/../nav/navbar.php';?>
    <div class="container mt-5">
        <h2>Редагувати профіль</h2>
        <form action="<?= BASE_URL?>/profile" method="POST">
            <div class="form-group">
                <label for="first_name">Ім'я</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $this->userModel->user['first_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Прізвище</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $this->userModel->user['last_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $this->userModel->user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Поточний пароль</label>
                <input type="password" class="form-control" id="password" name="password" value="" required>
            </div>
            <div class="form-group">
                <label for="newpassword">Новий пароль</label>
                <input type="password" class="form-control" id="newpassword" name="newpassword" value="" >
            </div>
            <div class="form-group">
                <label for="newpassword1">Новий пароль (повторити)</label>
                <input type="password" class="form-control" id="newpassword1" name="newpassword1" value="" >
            </div>            
            <button type="submit" class="btn btn-primary">Оновити</button>
        </form>
    </div>
    <!-- Bootstrap JS з CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
