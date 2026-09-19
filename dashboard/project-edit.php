<?php
$page_title = "All Dyne Ltd - Projetos";
require_once __DIR__ . '/../app/config/config.php';
require_once BASE_PATH . '/app/middleware/admin.php';

$id = $_GET['id'];
if (!empty($id)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM projects WHERE project_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $project = mysqli_fetch_assoc($result);

    if (!$project) {
        header("Location: " . BASE_URL . '/dashboard/index.php');
        exit;
    }
} else {
    header("Location: " . BASE_URL . '/dashboard/index.php');
    exit;
}

$name = $project['name'];
$status = $project['status'];

$alert = $_GET['alert'] ?? '';

require_once BASE_PATH . '/components/layout/head.php';
require_once BASE_PATH . '/components/layout/header.php';
?>

<div class="custom-main-container min-vh-100 section-spacer">
    <div class="row g-3">
        <div class="col-12">
            <span class="badge secondary red mb-3 <?= ($alert === "data_empty") ? "d-inline-block" : "d-none"?>">
                Preencha todos os campos obrigatórios.
            </span>
            <span class="badge secondary red mb-3 <?= ($alert === "update_error") ? "d-inline-block" : "d-none"?>">
                Falha ao atualizar dados.
            </span>
            <form name="filter-form" action="<?= BASE_URL ?>app/controllers/project-controller.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= e($id) ?>">
                <div class="d-flex flex-column gap-3">
                    <div class="card no-hover">
                        <div class="card-body">
                            <div class="form-content">
                                <div class="form-header">
                                    <div class="icon-box"><i class="ri-info-card-line"></i></div>
                                    <h3 class="form-title">Informações</h3>
                                </div>
                                <div class="form-body row g-3">
                                    <div class="form-field col-12">
                                        <label for="name">Nome</label>
                                        <input class="form-control" name="name_project" id="name_project"
                                            placeholder="Ex: Empresa X..." type="text" value="<?= e($name) ?>"
                                            minlength="3" maxlength="100" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card no-hover">
                        <div class="card-body">
                            <div class="form-content">
                                <div class="form-header">
                                    <div class="icon-box"><i class="ri-code-s-slash-line"></i></div>
                                    <h3 class="form-title">Andamento</h3>
                                </div>
                                <div class="form-body row g-3">
                                    <div class="form-field col-12">
                                        <label for="status">Status</label>
                                        <select class="form-select" name="status" id="status" placeholder="Status"
                                            autocomplete="off">
                                            <option value="Pendente" <?= ($status == 'Pendente') ? 'selected' : '' ?>>
                                                Pendente</option>
                                            <!-- CORREÇÃO 3: Ajustado de 'Desenvolvendo' para 'Desenvolvimento' para bater com o option value -->
                                            <option value="Desenvolvendo" <?= ($status == 'Desenvolvendo') ? 'selected' : '' ?>>Desenvolvendo</option>
                                            <option value="Concluído" <?= ($status == 'Concluído') ? 'selected' : '' ?>>
                                                Concluído</option>
                                            <option value="Cancelado" <?= ($status == 'Cancelado') ? 'selected' : '' ?>>
                                                Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex w-100 justify-content-end p-0">
                        <button type="submit" class="btn secondary">Salvar Alterações</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/components/layout/scripts.php'; ?>