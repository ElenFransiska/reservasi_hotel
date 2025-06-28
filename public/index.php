<?php

require_once '../config/config.php';
require_once '../app/controllers/AuthController.php';

$authController = new AuthController($db);
$authController->login();
?>
