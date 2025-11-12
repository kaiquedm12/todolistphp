<?php
session_start();
require_once __DIR__ . '/../../config/db_connect.php';
require_once __DIR__ . '/../../models/Task.php';

if (!isset($_SESSION['user_id'])) {
    exit('Usuário não autenticado');
}

$task = new Task($conn);
$tasks = $task->getAll($_SESSION['user_id']);
?>

<div class="row g-3">
<?php foreach ($tasks as $t): ?>
    <div class="col-md-4">
        <div class="card shadow-sm <?= $t['status'] === 'concluída' ? 'opacity-50' : '' ?>">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($t['title']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($t['description']) ?></p>
                <?php if (!empty($t['deadline'])): ?>
                    <p class="text-muted small mb-2">🕒 Prazo: <?= date('d/m/Y', strtotime($t['deadline'])) ?></p>
                <?php endif; ?>
                <p class="badge <?= $t['status'] === 'concluída' ? 'bg-success' : 'bg-warning text-dark' ?>">
                    <?= ucfirst($t['status']) ?>
                </p>
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-sm btn-outline-primary me-2 toggle"
                            data-id="<?= $t['id'] ?>"
                            data-status="<?= $t['status'] ?>">
                        <?= $t['status'] === 'pendente' ? 'Concluir' : 'Reabrir' ?>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete" data-id="<?= $t['id'] ?>">Excluir</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php if (empty($tasks)): ?>
    <p class="text-center text-muted mt-4">Nenhuma tarefa cadastrada ainda 😴</p>
<?php endif; ?>
