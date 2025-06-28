<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function validateUser ($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
