# Простий блог на PHP

Цей проект є прикладом простої CMS для блогу, написаної на PHP з використанням MySQL та Bootstrap. Користувачі можуть реєструватися, додавати пости та коментувати записи.

## Функціонал
- Реєстрація та авторизація користувачів
- Додавання, редагування та видалення постів
- Додавання коментарів до постів
- Панель адміністратора для управління користувачами та контентом

## Встановлення

1. **Завантажте репозиторій**
   ```sh
   git clone git clone https://github.com/natadenga/blog.git

   cd blog
   ```

2. **Запуск інсталятора**
   - Відкрийте у браузері `http://your-domain/`
   - Введіть дані для підключення до бази даних
   - Після завершення встановлення видаліть чи перейменуйте файл install/install.php
   - Якщо блог не у кореневій теці сайту, то в інсталяторі вкажіть назву теки починаючи з "/" Приклад: /blog

3. **Налаштуйте веб-сервер**
   - Упевніться, що `Apache` або `nginx` вказують на кореневу директорію проєкту
   - Увімкніть модуль `mod_rewrite` для коректної роботи посилань

4. **Перевірте роботу сайту**
   - Відкрийте `http://your-domain/` у браузері

## Використані технології
- **PHP** – серверна частина
- **MySQL** – база даних
- **Bootstrap** – стильове оформлення
- **PDO** – робота з базою даних

## Файли та структура
```
/blog
│── install/         # Файли для встановлення (будуть видалені після інсталяції)
│── assets/         # CSS, JS, зображення
│── controllers/    # Контролери
│── models/         # Моделі доступу
│── views/          # Файли відображення
│── index.php       # Головна сторінка
│── config.php      # Конфігураційний файл
│── .gitignore      # Файл ігнорування Git
│── README.md       # Інструкція по використанню
```

## Автор
Розроблено natadenga.

## Ліцензія
Цей проект розповсюджується за ліцензією MIT. Ви можете вільно використовувати та модифікувати його відповідно до умов ліцензії.


