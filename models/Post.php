<?php
class Post {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function createPost($user_id, $title, $content) {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $title, $content]);
    }

    public function getPostById($id) {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllPosts() {
        $stmt = $this->db->query("SELECT * FROM posts ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePost($id, $title, $content) {
        $stmt = $this->db->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        return $stmt->execute([$title, $content, $id]);
    }

// Отримати всі пости з інформацією про кількість коментарів
    public function getPostsWithComments() {
        $stmt = $this->db->prepare("
            SELECT 
                posts.id,
                posts.title,
                posts.created_at,
                users.first_name,
                users.last_name,
                COUNT(comments.id) AS comments_count
            FROM posts
            LEFT JOIN users ON posts.user_id = users.id
            LEFT JOIN comments ON posts.id = comments.post_id
            GROUP BY posts.id
            ORDER BY posts.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Видалити пост
    public function deletePost($id) {
        // Спочатку видаляємо всі коментарі до поста
        $stmt = $this->db->prepare("DELETE FROM comments WHERE post_id = ?");
        $stmt->execute([$id]);

        // Тепер видаляємо сам пост
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getCommentsById($id) {
        
    }
    
    public function getAuthorIdForPost($id) {
        $stmt = $this->db->prepare("
            SELECT user_id
            FROM posts
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetchOne(PDO::FETCH_ASSOC);
    }
    
}
?>
