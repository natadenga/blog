<?php
class Comment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addComment($user_id, $post_id, $content) {
        $stmt = $this->db->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $post_id, $content]);
    }

    public function getCommentsByPostId($post_id) {
        $stmt = $this->db->prepare("SELECT c.*,u.first_name, u.last_name FROM comments as c LEFT JOIN users as u ON (u.id = c.user_id) WHERE post_id = ? ORDER BY created_at DESC");
        $stmt->execute([$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteComment($id) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
