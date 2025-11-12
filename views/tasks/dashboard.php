<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2>Olá, <?= htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
    <form id="taskForm" class="mb-3">
        <input type="hidden" name="action" value="create">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="title" class="form-control" placeholder="Título" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="description" class="form-control" placeholder="Descrição">
            </div>
            <div class="col-md-3">
                <input type="date" name="deadline" class="form-control">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100">+</button>
            </div>
        </div>
    </form>

    <div id="taskList"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    function loadTasks() {
         $.post("../../controllers/taskController.php", { action: "list" }, function(response) {
        try {
            const tasks = JSON.parse(response);
            let html = `
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Data Limite</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            tasks.forEach(t => {
                html += `
                    <tr>
                        <td>${t.title}</td>
                        <td>${t.description || ""}</td>
                        <td>${t.status}</td>
                        <td>${t.deadline || ""}</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete" data-id="${t.id}">Excluir</button>
                            <button class="btn btn-sm btn-warning toggle" data-id="${t.id}" data-status="${t.status}">Alternar</button>
                        </td>
                    </tr>
                `;
            });
            html += "</tbody></table>";
            $("#taskList").html(html);
        } catch (err) {
            console.error("Erro ao carregar tarefas:", err, response);
            $("#taskList").html("<p class='text-danger'>Erro ao carregar tarefas.</p>");
        }
     });
    }

    loadTasks();

    $("#taskForm").on("submit", function(e) {
        e.preventDefault();
        $.post("../../controllers/taskController.php", $(this).serialize(), function() {
            loadTasks();
            $("#taskForm")[0].reset();
        });
    });

    $(document).on("click", ".delete", function() {
        const id = $(this).data("id");
        $.post("../../controllers/taskController.php", {action: "delete", id}, loadTasks);
    });

    $(document).on("click", ".toggle", function() {
        const id = $(this).data("id");
        const newStatus = $(this).data("status");
        $.post("../../controllers/taskController.php", {action: "toggle", id, newStatus}, loadTasks);
    });
});
</script>

</body>
</html>
