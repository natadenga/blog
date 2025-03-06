    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <!-- Ліворуч: Кнопка Головна -->
            <a class="navbar-brand"  href="<?=BASE_URL?>/">Мій блог</a>|&nbsp; 
            <a href="<?=BASE_URL?>/" class="navbar-item">Головна сторінка</a>
            <!-- Праворуч: Меню користувача -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown ms-auto">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <?php if (($_SESSION['user_role'] === "admin")): ?>
                        <li><a class="dropdown-item" href="<?=BASE_URL?>/admin">Адмін-панель</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="<?=BASE_URL?>/profile/<?=$_SESSION['user_id']?>">Мій профіль</a></li>
                        <li><a class="dropdown-item" href="<?=BASE_URL?>/logout">Вийти</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="ms-auto">
                    <a class="btn btn-primary" href="<?=BASE_URL?>/login">Увійти</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
