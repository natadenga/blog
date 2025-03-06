<?php
class User {
    private $db;
    public $user;
    public function __construct($db) {
        $this->db = $db;
        if(isset($_SESSION['user_id'])){
            $this->user = $this->getUserById($_SESSION['user_id']);
        }
    }
    
    public function register($first_name, $last_name, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$first_name, $last_name, $email, $hashed_password]);
    }
    
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, role,password FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUserByEmail($id) {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE email = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateProfile($id, $first_name, $last_name, $email) {
        $stmt = $this->db->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
        return $stmt->execute([$first_name, $last_name, $email, $id]);
    }
    
    public function updatePassword($id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashed_password, $id]);
    }
    
    public function setResetToken($email, $token) {
        $stmt = $this->db->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        return $stmt->execute([$token, $email]);
    }
    
    public function getUserByToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token = ?");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function clearResetToken($id) {
        $stmt = $this->db->prepare("UPDATE users SET reset_token = NULL WHERE id = ?");
        return $stmt->execute([$id]);
    }
// Отримати всіх користувачів
    public function getAllUsers() {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Видалити користувача
    public function deleteUser($id) {
        // Спочатку видаляємо всі пости користувача
        $stmt = $this->db->prepare("DELETE FROM posts WHERE user_id = ?");
        $stmt->execute([$id]);

        // Тепер видаляємо самого користувача
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }    
}
