<?php
$page_title = "All Dyne Ltd - Relatórios";
require_once __DIR__ . '/../app/config/config.php';
require_once BASE_PATH . '/app/middleware/admin.php';

$date_start = $_GET['date_start'] ?? '';
$date_end = $_GET['date_end'] ?? '';
$order = $_GET['order'] ?? 'DESC';

$stmt_projects = mysqli_prepare($conn, "SELECT COUNT(*) AS total_projects FROM projects WHERE date_register BETWEEN ? AND ?");
mysqli_stmt_bind_param($stmt_projects, "ss", $date_start, $date_end);
mysqli_stmt_execute($stmt_projects);
$result_projects = mysqli_stmt_get_result($stmt_projects);
$data_projects = mysqli_fetch_assoc($result_projects);
$total_projects = $data_projects['total_projects'] ?? 0;
mysqli_stmt_close($stmt_projects);

$stmt_revenue = mysqli_prepare($conn, "SELECT SUM(price) AS total_revenue FROM projects WHERE date_register BETWEEN ? AND ?");
mysqli_stmt_bind_param($stmt_revenue, "ss", $date_start, $date_end);
mysqli_stmt_execute($stmt_revenue);
$result_revenue = mysqli_stmt_get_result($stmt_revenue);
$data_revenue = mysqli_fetch_assoc($result_revenue);
$total_revenue = $data_revenue['total_revenue'] ?? 0;
mysqli_stmt_close($stmt_revenue);

$stmt_recent_projects = mysqli_prepare($conn, "SELECT projects.project_id, projects.name AS project_name, projects.price, projects.status, projects.date_register, users.name AS user_name, packages.name AS package_name
FROM projects INNER JOIN users ON projects.user_id = users.id INNER JOIN packages ON projects.package_id = packages.id WHERE projects.date_register BETWEEN ? AND ? ORDER BY projects.date_register $order");
mysqli_stmt_bind_param($stmt_recent_projects, "ss", $date_start, $date_end);
mysqli_stmt_execute($stmt_recent_projects);
$result_recent_projects = mysqli_stmt_get_result($stmt_recent_projects);
$recent_projects = mysqli_fetch_all($result_recent_projects, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_recent_projects);

require_once BASE_PATH . '/components/layout/head.php';
require_once BASE_PATH . '/components/layout/header.php';
?>

<div class="custom-main-container section-spacer">
    <div class="row g-3">
        <div class="col-12">
            <div class="card no-hover mb-3">
                <div class="card-body">
                    <form name="filter-form" id="filter-form" action="<?= BASE_URL ?>dashboard/reports.php"
                        method="GET">
                        <div class="form-content mb-3">
                            <div class="form-header">
                                <div class="icon-box"><i class="ri-search-line"></i></div>
                                <h3 class="form-title">Gerar Relatório</h3>
                            </div>
                            <div class="form-body row g-3">
                                <div class="form-field col-4">
                                    <label for="date_start">Início</label>
                                    <input class="form-control" name="date_start" id="date_start"
                                        placeholder="00/00/0000" type="date" value="<?= e($date_start) ?>" required />
                                </div>
                                <div class="form-field col-4">
                                    <label for="date_end">Fim</label>
                                    <input class="form-control" name="date_end" id="date_end" placeholder="00/00/0000"
                                        type="date" value="<?= e($date_end) ?>" required />
                                </div>
                                <div class="form-field col-4">
                                    <label for="order">Ordem</label>
                                    <select class="form-select" name="order" id="order" placeholder="order"
                                        autocomplete="off" required>
                                        <option value="ASC" <?= $order === 'ASC' ? 'selected' : '' ?>>Crescente</option>
                                        <option value="DESC" <?= $order === 'DESC' ? 'selected' : '' ?>>Decrescente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="d-flex w-100 justify-content-end p-0">
                <button type="submit" class="btn secondary" form="filter-form">Gerar Relatório</button>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card no-hover h-100">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="ri-contract-line"></i>
                    </div>
                    <h3 class="card-title">
                        <?= (empty($total_projects)) ? "Valor não determinado" : e($total_projects) ?>
                    </h3>
                    <p>Projetos registrados</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card no-hover h-100">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="ri-funds-line"></i>
                    </div>
                    <h3 class="card-title"><?= (empty($total_revenue)) ? "Valor não determinado" : "R$ " . e(number_format($total_revenue, 2, ',', '.')) ?>
                    </h3>
                    <p>Valor faturado</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card no-hover">
                <div class="card-body">
                    <div class="icon-box"><i class="ri-apps-line"></i></div>
                    <h3 class="card-title">Projetos</h3>
                    <p class="">Todos os projetos registrados no período.</p>
                    <hr>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Projeto</th>
                                        <th>Cliente</th>
                                        <th>Pacote</th>
                                        <th>Valor</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_projects)): ?>
                                        <?php foreach ($recent_projects as $project): ?>
                                            <tr>
                                                <td><?= $project['project_id'] ?></td>
                                                <td><?= $project['project_name'] ?></td>
                                                <td><?= $project['user_name'] ?></td>
                                                <td><?= $project['package_name'] ?></td>
                                                <td>R$ <?= number_format($project['price'], 2, ',', '.') ?></td>
                                                <td><?= date('d/m/Y', strtotime($project['date_register'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                Nenhum projeto encontrado.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/components/layout/scripts.php'; ?>