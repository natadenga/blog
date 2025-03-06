<?php
class PostController {
    private $postModel;
    private $userModel;
    private $commentModel;
    private $db;

    public function __construct($post,$user,$comment) {
        $this->postModel = $post;
        $this->userModel = $user;
        $this->commentModel = $comment;
    }

    // Створення поста
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $title = $_POST['title'];
            $content = $_POST['content'];

            if ($this->postModel->createPost($user_id, $title, $content)) {
                header('Location: '.BASE_URL.'/');
            } else {
                echo "Помилка при створенні поста";
            }
        } else {
            require_once './views/posts/create.php';
        }
    }

    // Редагування поста
    public function edit($id) {
        if (is_array($this->userModel->user) && $this->isAuthor($id)){
            
        $post = $this->postModel->getPostById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];

            if ($this->postModel->updatePost($id, $title, $content)) {
                header('Location: '.BASE_URL.'/post/' . $id);
            } else {
                echo "Помилка при оновленні поста";
            }
        } else {
            require_once './views/posts/create.php';
        }
        }else {
            echo "Ви не маєте доступу до цього розділу!";
        }
    }

    // Перегляд поста
    public function show($id) {
        $post = $this->postModel->getPostById($id);
        $comments = $this->commentModel->getCommentsByPostId($id);
        require_once './views/posts/show.php';
    }

    // Перегляд всіх постів
    public function index() {
        return $this->postModel->getAllPosts();
        //require_once './views/posts/index.php';
    }
    
    // Перевірка на автора поста
    public function isAuthor($id) {
        return $this->userModel->user["role"] === "admin" || $this->userModel->user["id"] === $this->postModel->getAuthorIdForPost($id);
    }
}
