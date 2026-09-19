<?php
//require_once BASE_PATH . '/app/controllers/projects-filter.php';
?>

<div class="table-responsive table-container">
    <table class="table align-middle text-nowrap">
        <thead>
            <tr>
                <th>Id</th>
                <th>Projeto</th>
                <th>Cliente</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
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
                    <tr>
                        <td><?= $project['project_id'] ?></td>
                        <td><?= $project['project_name'] ?></td>
                        <td><?= $project['user_name'] ?></td>
                        <td><?= date('d/m/Y', strtotime($project['date_register'])) ?></td>
                        <td><span class="badge secondary <?= $status_class?>"><?= $project['status'] ?></span></td>
                        <td><a class="btn secondary" href="<?= BASE_URL ?>dashboard/project-edit.php?id=<?= $project['project_id'] ?>">Editar</a></td>
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