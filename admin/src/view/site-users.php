<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Gerenciar Usuários do Site</h1>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md">
                <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome ou e-mail..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<?php consume_status(); ?>

<div class="row">
    <?php if (empty($site_users)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">Nenhum usuário encontrado.</div>
        </div>
    <?php else: ?>
        <?php foreach ($site_users as $user): ?>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card user-admin-card h-100">
                    <div class="card-body d-flex">
                        <div class="user-avatar me-3">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($user->full_name); ?></h5>
                            <p class="card-text text-muted mb-2"><?php echo htmlspecialchars($user->email); ?></p>
                            <p class="card-text text-muted small"><i class="bi bi-telephone-fill"></i> <?php echo htmlspecialchars($user->phone_number ?? 'Não informado'); ?></p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#"
                                        data-bs-toggle="modal" data-bs-target="#editSiteUserModal"
                                        data-id="<?php echo $user->id; ?>"
                                        data-full_name="<?php echo htmlspecialchars($user->full_name); ?>"
                                        data-email="<?php echo htmlspecialchars($user->email); ?>"
                                        data-phone_number="<?php echo htmlspecialchars($user->phone_number ?? ''); ?>">
                                        <i class="bi bi-pencil me-2"></i>Editar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteSiteUserModal"
                                        data-id="<?php echo $user->id; ?>"
                                        data-name="<?php echo htmlspecialchars($user->full_name); ?>">
                                        <i class="bi bi-trash me-2"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<nav class="d-flex justify-content-center mt-4">
    <?php $site_user_controller->display_pagination($total_items, $items_per_page); ?>
</nav>

<div class="modal fade" id="editSiteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuário do Site</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSiteUserForm" method="post" action="<?php echo ADMIN_URL; ?>site-users">
                <div class="modal-body">
                    <input type="hidden" name="action_type" value="edit">
                    <input type="hidden" name="user_id" id="editSiteUserId">

                    <div class="mb-3">
                        <label for="editSiteUserFullName" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="editSiteUserFullName" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSiteUserEmail" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="editSiteUserEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSiteUserPhone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="editSiteUserPhone" name="phone_number">
                    </div>
                    <div class="mb-3">
                        <label for="editSiteUserPassword" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="editSiteUserPassword" name="password">
                        <small class="form-text text-muted">Deixe em branco para não alterar a senha.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteSiteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="deleteSiteUserForm" method="post" action="<?php echo ADMIN_URL; ?>site-users">
                <div class="modal-body">
                    <input type="hidden" name="action_type" value="delete">
                    <input type="hidden" name="user_id" id="deleteSiteUserId">
                    <p>Tem certeza que deseja excluir o usuário <strong id="siteUserNameToDelete"></strong>?</p>
                    <p class="text-danger">Esta ação não pode ser desfeita e removerá permanentemente o usuário e suas doações associadas.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>