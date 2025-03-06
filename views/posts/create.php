<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($post) ? 'Редагувати пост' : 'Створити пост' ?></title>
    <!-- Bootstrap CSS з CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Quill CSS з CDN -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
    <?php if ($_SESSION["user_role"] === "admin") include __DIR__ .'/../nav/navbar_admin.php'; else  include __DIR__ .'/../nav/navbar.php';?>
    <div class="container mt-5">
        <h2><?= isset($post) ? 'Редагувати пост' : 'Створити пост' ?></h2>
        <form action="<?= BASE_URL . (isset($post) ? '/post/' . $post['id'].'/edit' : '/create') ?>" method="POST">
            <div class="form-group">
                <label for="title">Заголовок</label>
                <input type="text" class="form-control" id="title" name="title" required 
                       value="<?= isset($post) ? htmlspecialchars($post['title']) : '' ?>">
            </div>
            <div class="form-group mt-4">
                <label for="content">Контент</label>
                <!-- Контейнер для Quill редактора -->
                <div id="content" style="height: 200px;"><?= isset($post) ? htmlspecialchars_decode($post['content']) : '' ?></div>
                <input type="hidden" name="content" id="contentInput"> <!-- Сховане поле для збереження даних -->
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($post) ? 'Оновити' : 'Створити' ?></button>
        </form>
    </div>

    <!-- Quill JS з CDN -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!-- Bootstrap JS з CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Ініціалізація Quill редактора
        var quill = new Quill('#content', {
            theme: 'snow',
            placeholder: 'Напишіть контент...',
            modules: {
                toolbar: [
                    [{ 'header': '1' }, { 'header': '2' }, { 'font': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['bold', 'italic', 'underline'],
                    ['link'],
                    ['blockquote', 'code-block'],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        // Якщо є існуючий контент, вставляємо його у редактор
        <?php if (isset($post)): ?>
            quill.root.innerHTML = <?= json_encode(htmlspecialchars_decode($post['content'])); ?>;
        <?php endif; ?>

        // Після відправки форми зберігаємо контент у приховане поле
        document.querySelector('form').onsubmit = function() {
            var content = quill.root.innerHTML;
            document.getElementById('contentInput').value = content;
        };
    </script>
</body>
</html>
