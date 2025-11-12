<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../models/Task.php';

file_put_contents(__DIR__ . '/../debug_log.txt', "Entrou no taskController\n", FILE_APPEND);

$task = new Task($conn);

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $task->create($userId, $_POST['title'], $_POST['description'], $_POST['deadline']);
    }

    if ($action === 'update') {
        $task->update($_POST['id'], $_POST['title'], $_POST['description'], $_POST['deadline']);
    }

    if ($action === 'delete') {
        $task->delete($_POST['id']);
    }

    if ($action === 'toggle') {
        $newStatus = $_POST['newStatus'] === 'pendente' ? 'concluída' : 'pendente';
        $task->toggleStatus($_POST['id'], $newStatus);
    }

    // ✅ NOVO BLOCO: listar tarefas
    if ($action === 'list') {
        $tasks = $task->getAllByUser($userId);
        echo json_encode($tasks);
        exit;
    }

    file_put_contents(__DIR__ . '/../debug_log.txt', print_r($_POST, true), FILE_APPEND);
    exit;
}
?>
