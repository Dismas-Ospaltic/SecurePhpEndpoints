<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

class User {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function createUser($email, $password) {
        $user_id = Uuid::uuid4()->toString(); // Generate UUID
        $stmt = $this->mysqli->prepare("INSERT INTO users (user_id, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_id, $email, $password);
        return $stmt->execute() ? $user_id : false;
    }

    public function getUserByEmail($email) {
        $stmt = $this->mysqli->prepare("SELECT user_id, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserById($user_id) {
        $stmt = $this->mysqli->prepare("SELECT id,user_id, email, created_at FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $user_id); // Bind as string, since UUIDs are strings
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
}
?>
