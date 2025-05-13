<?php

class AuthController {

    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

// Реєстрація користувача
    public function register() {
        // Перевірка, чи надіслано форму
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Отримання даних з форми
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Перевірка, чи вже існує користувач з таким email
            if ($this->userModel->getUserByEmail($email)) {
                $message = "Користувач з таким email вже існує.";
            } else {
                // Якщо все ок, реєстрація
                if ($this->userModel->register($first_name, $last_name, $email, $password)) {
                    $message = "Реєстрація успішна!";
                } else {
                    $message = "Помилка реєстрації.";
                }
            }

            // Зберігаємо повідомлення в сесії
            session_start();
            $_SESSION['message'] = $message;

            // Редирект на головну сторінку
            header('Location: ' . BASE_URL . '/');
            exit;
        } else {
            // Якщо форма не була надіслана, відображаємо її
            require_once './views/auth/register.php';
        }
    }

    // Вхід користувача
    public function login() {
        // Перевірка, чи надіслано форму
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Отримання даних з форми
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Перевірка логіну користувача
            $user = $this->userModel->login($email, $password);
            if ($user) {
                session_start();  // Початок сесії
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['first_name'] . " " . $user['last_name'];
                header('Location: ' . BASE_URL . '/'); // Переадресація після успішного входу
                exit();
            } else {
                $message = "Невірний email або пароль.";
                return $message;
            }
        } else {
            // Якщо форма не була надіслана, відображаємо її
            require_once './views/auth/login.php';
        }
    }

    // Вихід користувача
    public function logout() {
        session_start();  // Початок сесії перед її завершенням
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/'); // Переадресація після виходу
        exit();
    }

    // Скидання пароля
    public function resetPassword($email, $token) {
        // Перевірка токену скидання пароля
        $user = $this->userModel->getUserByToken($token);
        if ($user && $user['email'] == $email) {
            // Перевірка токену пройшла успішно
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $new_password = $_POST['new_password'];
                if ($this->userModel->updatePassword($user['id'], $new_password)) {
                    $this->userModel->clearResetToken($user['id']);  // Очищення токену після оновлення пароля
                    return "Пароль успішно змінено.";
                }
                return "Помилка при зміні пароля.";
            }

            // Форма для введення нового пароля
            require_once './views/auth/reset_password_form.php';
        }
        return "Невірний токен або email.";
    }

    // Створення форми скидання пароля (для відправки запиту на скидання)
    public function sendResetPasswordEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            // Перевірка чи є користувач з таким email
            $user = $this->userModel->getUserByEmail($email);
            if ($user) {
                // Генерація токену для скидання пароля
                $token = bin2hex(random_bytes(16));  // Генерація випадкового токену
                $this->userModel->setResetToken($user['id'], $token);

                if($this->sendResetEmail($email, $token)){
                    echo "На вашу електронну пошту надіслано інструкції для скидання пароля.";
                }else {
                    echo "Помилка відправлення листа";
                }
            }else {
                echo "Користувача з таким email не знайдено.";
            }
        } else {
            // Форма для введення email для скидання пароля
            require_once './views/auth/reset_password.php';
        }
    }

    function sendResetEmail($email, $token) {
        $subject = "Скидання пароля";
        $resetLink = "https://example.com/reset-password?token=" . urlencode($token);
        $message = "Перейдіть за посиланням для скидання пароля: " . $resetLink;

        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Reply-To: support@example.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($email, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    public function editProfile() {
        if (is_array($this->userModel->user)) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_SESSION['user_id'];
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $newpassword = $_POST['newpassword'];
                $newpassword1 = $_POST['newpassword1'];

                // Отримуємо поточного користувача
                $user = $this->userModel->getUserById($id);

                // Перевіряємо, чи змінено email
                if ($email !== $user['email']) {
                    $existingUser = $this->userModel->getUserByEmail($email);
                    if ($existingUser) {
                        echo "Помилка: цей email вже використовується іншим користувачем!";
                        return;
                    }
                }

                // Перевірка поточного пароля
                if (!password_verify($password, $this->userModel->user['password'])) {
                    echo "Помилка: поточний пароль введено неправильно!";
                    return;
                }

                // Оновлення профілю
                if ($this->userModel->updateProfile($id, $first_name, $last_name, $email)) {
                    $_SESSION['user_name'] = $first_name . " " . $last_name;

                    // Якщо введено новий пароль, оновлюємо його
                    if (!empty($newpassword) && $newpassword === $newpassword1) {
                        $this->userModel->updatePassword($id, $newpassword);
                    } elseif (!empty($newpassword) && $newpassword !== $newpassword1) {
                        echo "Помилка: нові паролі не співпадають!";
                        return;
                    }

                    header('Location: ' . BASE_URL . '/profile');
                    exit;
                } else {
                    echo "Помилка при оновленні профілю";
                }
            } else {
                require_once './views/profile/edit.php';
            }
        } else {
            echo "Ви не маєте доступу до цього розділу!";
        }
    }
}

?>
