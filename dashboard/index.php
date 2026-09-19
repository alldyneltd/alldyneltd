<?php
$page_title = "All Dyne Ltd - Dashboard";
require_once __DIR__ . '/../app/config/config.php';
require_once BASE_PATH . '/app/middleware/admin.php';

$stmt_users = mysqli_prepare($conn, "SELECT COUNT(*) AS total_users FROM users WHERE role = 'Cliente'");
mysqli_stmt_execute($stmt_users);
$result_users = mysqli_stmt_get_result($stmt_users);
$data_users = mysqli_fetch_assoc($result_users);
$total_users = $data_users['total_users'] ?? 0;
mysqli_stmt_close($stmt_users);

$stmt_revenue = mysqli_prepare($conn, "SELECT SUM(price) AS total_revenue FROM projects WHERE status != 'Cancelado'");
mysqli_stmt_execute($stmt_revenue);
$result_revenue = mysqli_stmt_get_result($stmt_revenue);
$data_revenue = mysqli_fetch_assoc($result_revenue);
$total_revenue = $data_revenue['total_revenue'] ?? 0.00;
mysqli_stmt_close($stmt_revenue);

$stmt_projects_completed = mysqli_prepare($conn, "SELECT COUNT(*) AS complete_projects FROM projects WHERE status = 'Concluído'");
mysqli_stmt_execute($stmt_projects_completed);
$result_projects_completed = mysqli_stmt_get_result($stmt_projects_completed);
$data_projects_completed = mysqli_fetch_assoc($result_projects_completed);
$total_projects_completed = $data_projects_completed['complete_projects'] ?? 0;
mysqli_stmt_close($stmt_projects_completed);

$stmt_projects_pending = mysqli_prepare($conn, "SELECT COUNT(*) AS pending_projects FROM projects WHERE status = 'Pendente'");
mysqli_stmt_execute($stmt_projects_pending);
$result_projects_pending = mysqli_stmt_get_result($stmt_projects_pending);
$data_projects_pending = mysqli_fetch_assoc($result_projects_pending);
$total_projects_pending = $data_projects_pending['pending_projects'] ?? 0;
mysqli_stmt_close($stmt_projects_pending);

$stmt_projects = mysqli_prepare($conn, "SELECT projects.project_id, projects.name AS project_name, projects.price, projects.status, projects.date_register, users.name AS user_name, packages.name AS package_name
FROM projects INNER JOIN users  ON projects.user_id = users.id INNER JOIN packages  ON projects.package_id = packages.id ORDER BY projects.date_register DESC, projects.project_id DESC LIMIT 5");
mysqli_stmt_execute($stmt_projects);
$result_projects = mysqli_stmt_get_result($stmt_projects);
$projects = mysqli_fetch_all($result_projects, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_projects);

$stmt_users = mysqli_prepare($conn, "SELECT id, name, email, date_register, status FROM users ORDER BY date_register DESC, id DESC LIMIT 5");
mysqli_stmt_execute($stmt_users);
$result_users = mysqli_stmt_get_result($stmt_users);
$users = mysqli_fetch_all($result_users, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_users);

require_once BASE_PATH . '/components/layout/head.php';
require_once BASE_PATH . '/components/layout/header.php';
?>

<div class="custom-main-container section-spacer">
    <div class="row g-3">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card no-hover h-100">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="ri-group-line"></i>
                    </div>
                    <h3 class="card-title"><?= e($total_users)?></h3>
                    <p>Clientes Totais</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card no-hover h-100">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="ri-funds-line"></i>
                    </div>
                    <h3 class="card-title">R$ <?= e(number_format($total_revenue, 2, ',', '.'))?></h3>
                    <p>Receita Total</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card no-hover h-100">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="ri-contract-line"></i>
                    </div>
                    <h3 class="card-title"><?= e($total_projects_completed)?></h3>
                    <p>Projetos Completos</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card no-hover h-100">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="ri-alert-line"></i>
                    </div>
                    <h3 class="card-title"><?= e($total_projects_pending)?></h3>
                    <p>Projetos Pendentes</p>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card no-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-box"><i class="ri-apps-line"></i></div>
                        <a href="<?= BASE_URL ?>dashboard/projects.php" class="btn secondary">Ver Todos</a>
                    </div>
                    <h3 class="card-title">Projetos Recentes</h3>
                    <p>5 Projetos mais recentes cadastrados.</p>
                    <hr>
                    <div>
                        <?php require_once BASE_PATH . '/components/shared/table-projects.php'?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card no-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-box"><i class="ri-group-line"></i></div>
                        <a href="<?= BASE_URL ?>dashboard/users.php" class="btn secondary">Ver Todos</a>
                    </div>
                    <h3 class="card-title">Usuários Recentes</h3>
                    <p>5 Usuários mais recentes cadastrados.</p>
                    <hr>
                    <div>
                        <?php require_once BASE_PATH . '/components/shared/table-users.php'?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/components/layout/scripts.php' ?>