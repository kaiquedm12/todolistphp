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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Olá, <?= htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
        <a href="#" id="logout" class="btn btn-outline-danger btn-sm">Sair</a>
    </div>

    <!-- FILTROS E BUSCA -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <select id="filterStatus" class="form-select">
                <option value="">Todos</option>
                <option value="pendente">Pendentes</option>
                <option value="concluída">Concluídas</option>
            </select>

            <select id="sortBy" class="form-select">
                <option value="recent">Mais Recentes</option>
                <option value="oldest">Mais Antigas</option>
                <option value="deadline">Por Prazo</option>
            </select>
        </div>

        <div class="w-25">
            <input type="text" id="searchTask" class="form-control" placeholder="🔍 Buscar tarefa...">
        </div>
    </div>

    <!-- FORMULÁRIO DE CRIAÇÃO -->
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

    // === FUNÇÃO PRINCIPAL DE CARREGAR TAREFAS ===
    function loadTasks() {
        const status = $("#filterStatus").val();
        const sort = $("#sortBy").val();
        const search = $("#searchTask").val();

        $.post("../../controllers/taskController.php", {
            action: "list",
            status,
            sort,
            search
        }, function(response) {
            try {
                const tasks = JSON.parse(response);
                let html = `
                    <table class="table table-striped align-middle">
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
                if (tasks.length === 0) {
                    html += `<tr><td colspan="5" class="text-center text-muted">Nenhuma tarefa encontrada.</td></tr>`;
                } else {
                    tasks.forEach(t => {
                        html += `
                            <tr>
                                <td>${t.title}</td>
                                <td>${t.description || ""}</td>
                                <td>${t.status}</td>
                                <td>${t.deadline || ""}</td>
                                <td>
                                    <button class="btn btn-sm btn-secondary edit" 
                                        data-id="${t.id}" 
                                        data-title="${t.title}" 
                                        data-description="${t.description}" 
                                        data-deadline="${t.deadline || ''}">
                                        ✏️ Editar
                                    </button>
                                    <button class="btn btn-sm btn-warning toggle" data-id="${t.id}" data-status="${t.status}">Alternar</button>
                                    <button class="btn btn-sm btn-danger delete" data-id="${t.id}">Excluir</button>
                                </td>
                            </tr>
                        `;
                    });
                }
                html += "</tbody></table>";
                $("#taskList").html(html);
            } catch (err) {
                console.error("Erro ao carregar tarefas:", err, response);
                $("#taskList").html("<p class='text-danger'>Erro ao carregar tarefas.</p>");
            }
        });
    }

    // === CHAMADAS E EVENTOS ===
    loadTasks();

    $("#taskForm").on("submit", function(e) {
        e.preventDefault();
        $.post("../../controllers/taskController.php", $(this).serialize(), function() {
            loadTasks();
            $("#taskForm")[0].reset();
            $("input[name='action']").val("create");
            $("input[name='id']").remove();
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

    $(document).on("click", ".edit", function() {
        const id = $(this).data("id");
        const title = $(this).data("title");
        const description = $(this).data("description");
        const deadline = $(this).data("deadline");

        $("input[name='title']").val(title);
        $("input[name='description']").val(description);
        $("input[name='deadline']").val(deadline);
        $("input[name='action']").val("update");

        if (!$("input[name='id']").length) {
            $("<input>").attr({
                type: "hidden",
                name: "id",
                value: id
            }).appendTo("#taskForm");
        } else {
            $("input[name='id']").val(id);
        }
    });

    // Filtros e busca
    $("#filterStatus, #sortBy").on("change", loadTasks);
    $("#searchTask").on("keyup", function() {
        clearTimeout(this.delay);
        this.delay = setTimeout(loadTasks, 400);
    });

    // Logout
    $("#logout").on("click", function(e) {
        e.preventDefault();
        $.post("../../controllers/userController.php", {action: "logout"}, function() {
            window.location.href = "../auth/login.php";
        });
    });
});
</script>

</body>
</html>
