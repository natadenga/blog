<?php
class CommentController {
    private $commentModel;
    private $userModel;

    public function __construct($comment, $user) {
        $this->userModel = $user;
        $this->commentModel = $comment;
    }

    // Додавання коментаря
    public function addComment($post_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $content = $_POST['comment'];

            if ($this->commentModel->addComment($user_id, $post_id, $content)) {
                header('Location: '.BASE_URL.'/post/' . $post_id);
            } else {
                echo "Помилка при додаванні коментаря";
            }
        }
    }
}
