<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Logs de Atividade do Sistema</h1>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md">
                <input type="text" name="search" class="form-control" placeholder="Pesquisar por usuário ou ação..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                 <select name="action_type" class="form-select">
                    <option value="">Todas as Ações</option>
                    <option value="create" <?php echo (($_GET['action_type'] ?? '') == 'create') ? 'selected' : ''; ?>>Criação</option>
                    <option value="update" <?php echo (($_GET['action_type'] ?? '') == 'update') ? 'selected' : ''; ?>>Atualização</option>
                    <option value="delete" <?php echo (($_GET['action_type'] ?? '') == 'delete') ? 'selected' : ''; ?>>Exclusão</option>
                    <option value="login" <?php echo (($_GET['action_type'] ?? '') == 'login') ? 'selected' : ''; ?>>Login/Logout</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<?php consume_status(); ?>

<div class="list-group">
    <?php if (empty($logs)): ?>
        <div class="alert alert-info text-center">Nenhum log de atividade encontrado para os filtros selecionados.</div>
    <?php else: ?>
        <?php foreach ($logs as $log): ?>
            <?php
            // Lógica para definir ícone e cor com base na ação
            $icon = 'bi-info-circle';
            $color = 'text-secondary';
            if (str_contains($log->action, 'create') || str_contains($log->action, 'register')) {
                $icon = 'bi-plus-circle-fill';
                $color = 'text-success';
            } elseif (str_contains($log->action, 'update')) {
                $icon = 'bi-pencil-fill';
                $color = 'text-primary';
            } elseif (str_contains($log->action, 'delete')) {
                $icon = 'bi-trash-fill';
                $color = 'text-danger';
            } elseif (str_contains($log->action, 'login')) {
                $icon = 'bi-key-fill';
                $color = 'text-info';
            } elseif (str_contains($log->action, 'logout')) {
                $icon = 'bi-door-closed-fill';
                $color = 'text-warning';
            }
            ?>
            <div class="list-group-item list-group-item-action">
                <div class="d-flex w-100 align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <i class="bi <?php echo $icon; ?> <?php echo $color; ?> fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1">
                            <strong class="me-1"><?php echo htmlspecialchars($log->admin_user_name); ?></strong>
                            <?php echo htmlspecialchars($log->action); ?>
                            <?php if ($log->target_type && $log->target_id): ?>
                                em <span class="fw-bold"><?php echo htmlspecialchars($log->target_type) . ' (ID: ' . $log->target_id . ')'; ?></span>
                            <?php endif; ?>
                        </p>
                        <small class="text-muted"><?php echo $log->created_at->format('d/m/Y H:i:s'); ?> &bull; IP: <?php echo htmlspecialchars($log->ip_address); ?></small>
                    </div>
                    <?php if ($log->details): ?>
                        <div class="flex-shrink-0 ms-3">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#logDetailsModal" data-details='<?php echo htmlspecialchars($log->details); ?>'>
                                Ver Detalhes
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<nav class="d-flex justify-content-center mt-4">
    <?php $log_controller->display_pagination($total_items, $items_per_page); ?>
</nav>

<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>As seguintes informações foram registradas para esta ação:</p>
                <pre class="bg-light p-3 rounded"><code></code></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>