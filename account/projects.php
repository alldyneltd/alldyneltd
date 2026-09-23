<?php
$page_title = "All Dyne Ltd - Projetos";
require_once __DIR__ . '/../app/config/config.php';
require_once BASE_PATH . '/app/middleware/auth.php';

$id = $_SESSION['id'] ?? '';
$stmt = mysqli_prepare($conn, "SELECT projects.project_id, projects.name AS project_name, projects.price, projects.status, projects.date_register, users.name AS user_name, packages.name AS package_name, packages.description AS package_description
FROM projects INNER JOIN users ON projects.user_id = users.id INNER JOIN packages ON projects.package_id = packages.id WHERE projects.user_id = ? ORDER BY projects.date_register DESC, projects.project_id DESC");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result_projects = mysqli_stmt_get_result($stmt);

$projects = mysqli_fetch_all($result_projects, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

require_once BASE_PATH . '/components/layout/head.php';
require_once BASE_PATH . '/components/layout/header.php';
?>

<div class="custom-main-container section-spacer">
    <div class="row g-3">
        <div class="col-12">
            <div class="card no-hover">
                <div class="card-body">
                    <div class="icon-box"><i class="ri-apps-line"></i></div>
                    <h3 class="card-title">Projetos do Usuário</h3>
                    <p>Veja todos os seus projetos registrados e acompanhe o seu desenvolvimento.</p>
                    <hr>
                    <div class="projects-list overflow-x-hidden">
                        <div class="row g-3">
                            <?php if (!empty($projects)): ?>
                                <?php foreach ($projects as $project): ?>
                                    <?php
                                    $status_class = 'yellow'; // Classe padrão
                            
                                    if ($project['status'] === 'Concluído') {
                                        $status_class = 'green';
                                    } else if ($project['status'] === 'Desenvolvendo') {
                                        $status_class = '';
                                    } else if ($project['status'] === 'Cancelado') {
                                        $status_class = 'red';
                                    }
                                    ?>
                                    <div class="col-12 col-md-6">
                                        <div class="card no-hover h-100">
                                            <div class="card-body d-flex flex-column">
                                                <div>
                                                    <h3 class="card-title col-6"><?= e($project['project_name'])?></h3>
                                                    <p>Projeto: <?= $project['project_id']?></p>
                                                    <hr>
                                                </div>
                                                <div>
                                                    <span class="span-highlight">Descrição</span>
                                                    <p><?= e($project['package_description'])?></p>
                                                </div>
                                                <div class="d-flex flex-wrap gap-3 justify-content-between mt-auto">
                                                    <div>
                                                        <span class="span-highlight">Pacote</span>
                                                        <p><?= e($project['package_name'])?></p>
                                                    </div>
                                                    <div>
                                                        <span class="span-highlight">Investimento</span>
                                                        <p>R$ <?= e($project['price'])?></p>
                                                    </div>
                                                    <div>
                                                        <span class="span-highlight">Data de Pagamento</span>
                                                        <p><?= e($project['date_register'])?></p>
                                                    </div>
                                                </div>
                                                <span class="badge secondary top <?= $status_class?>"><?= e($project['status'])?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="text-center">
                                        <p>Nenhum projeto encontrado.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/components/layout/scripts.php'; ?>