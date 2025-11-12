<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit('Usuário não logado.');
}

?>
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
    <tbody id="taskBody"></tbody>
</table>

<script>
$(document).ready(function() {
    $.post("../../controllers/taskController.php", { action: "list" }, function(tasks) {
        try {
            const data = JSON.parse(tasks);
            let html = "";
            data.forEach(t => {
                html += `
                    <tr>
                        <td>${t.title}</td>
                        <td>${t.description ?? ''}</td>
                        <td>${t.status}</td>
                        <td>${t.deadline ?? ''}</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete" data-id="${t.id}">Excluir</button>
                            <button class="btn btn-sm btn-warning toggle" data-id="${t.id}" data-status="${t.status}">Alternar</button>
                        </td>
                    </tr>
                `;
            });
            $("#taskBody").html(html);
        } catch (e) {
            console.error("Erro ao carregar tarefas:", e, tasks);
        }
    });
});
</script>
