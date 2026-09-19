<?php
$page_title = "All Dyne Ltd - Projetos";
require_once __DIR__ . '/../app/config/config.php';
require_once BASE_PATH . '/app/middleware/admin.php';

$filter = $_GET['filter'] ?? '';

if ($filter === '') {
    $stmt = mysqli_prepare($conn, "SELECT projects.project_id, projects.name AS project_name,projects.price, projects.status, projects.date_register, users.name AS user_name, packages.name AS package_name
    FROM projects INNER JOIN users  ON projects.user_id = users.id INNER JOIN packages  ON projects.package_id = packages.id ORDER BY projects.date_register DESC, projects.project_id DESC");

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} else {
    $param = "%{$filter}%";
    $stmt = mysqli_prepare($conn, "SELECT projects.project_id, projects.name AS project_name, projects.price, projects.status, projects.date_register, users.name AS user_name, packages.name AS package_name
    FROM projects INNER JOIN users  ON projects.user_id = users.id INNER JOIN packages  ON projects.package_id = packages.id WHERE projects.project_id LIKE ? OR projects.name LIKE ? OR users.name LIKE ? OR projects.status LIKE ? ORDER BY projects.date_register DESC, projects.project_id DESC");

    mysqli_stmt_bind_param($stmt, "ssss", $param, $param, $param, $param);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

require_once BASE_PATH . '/components/layout/head.php';
require_once BASE_PATH . '/components/layout/header.php';
?>

<div class="custom-main-container section-spacer">
    <div class="row g-3">
        <div class="col-12">
            <div class="card no-hover">
                <div class="card-body">
                    <form name="filter-form" id="filter-form" action="<?= BASE_URL ?>dashboard/projects.php" method="GET">
                        <div class="form-content">
                            <div class="form-header">
                                <h3 class="card-title">Filtrar Projetos</h3>
                            </div>
                            <div class="form-body row g-3">
                                <div class="form-field col-12">
                                    <label for="filtert">Filtro</label>
                                    <input class="form-control" name="filter" id="filter"
                                        placeholder="Digite algum dado..." type="text" maxlength="100" value="<?= e($filter) ?>">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button class="btn secondary" form="filter-form">Filtrar</button>
        </div>
        <div class="col-12">
            <div class="card no-hover">
                <div class="card-body">
                    <div class="icon-box"><i class="ri-apps-line"></i></div>
                    <h3 class="card-title">Projetos</h3>
                    <p>Todos os projetos cadastrados.</p>
                    <hr>
                    <div>
                        <?php require_once BASE_PATH . '/components/shared/table-projects.php'?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/components/layout/scripts.php';?>