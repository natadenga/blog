<?php
class AdminController {
    private $postModel;
    private $userModel;

    public function __construct($postModel, $userModel) {
        $this->postModel = $postModel;
        $this->userModel = $userModel;
    }



    public function deleteUser($id) {
        // Видалення користувача
        $this->userModel->deleteUser($id);
        header('Location: /admin');
        exit();
    }

    public function getUsers() {
        // Отримуємо список користувачів
        return $this->userModel->getAllUsers();
    }
    // Отримати всі пости
    public function getPosts() {
        return $this->postModel->getPostsWithComments();
    }

    // Видалити пост
    public function deletePost($id) {
        $this->postModel->deletePost($id);
        header('Location: /admin');
        exit();
    }



}
?>


