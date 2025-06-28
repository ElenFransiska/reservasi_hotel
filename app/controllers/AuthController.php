<?php

session_start();
require_once '../config/config.php';
require_once '../app/models/User.php';

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->validateUser ($username, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Username atau password salah!";
                require '../app/views/login.php';
            }
        } else {
            require '../app/views/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
?>
