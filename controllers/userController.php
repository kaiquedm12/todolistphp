<?php
session_start();
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/db_connect.php';

$user = new User($conn);

// Cadastro
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $result = $user->register($name, $email, $password);

    if ($result === true) {
        header("Location: ../views/auth/login.php?success=1");
        exit;
    } else {
        header("Location: ../views/auth/register.php?error=" . urlencode($result));
        exit;
    }
}

// Login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $userData = $user->login($email, $password);

    if ($userData) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_name'] = $userData['name'];
        header("Location: ../views/tasks/dashboard.php");
        exit;
    } else {
        header("Location: ../views/auth/login.php?error=Credenciais inválidas");
        exit;
    }
}

// Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: ../views/auth/login.php");
    exit;
}
?>
