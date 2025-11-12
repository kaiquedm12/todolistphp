<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: views/auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - ToDo System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-success">
        <h4>Bem-vindo, <?= htmlspecialchars($_SESSION['user_name']); ?>!</h4>
        <a href="controllers/userController.php?action=logout" class="btn btn-danger mt-3">Sair</a>
    </div>
</div>
</body>
</html>
