<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Gerenciar Usuários Admin</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal">
        <i class="bi bi-plus-circle me-2"></i>Novo Usuário
    </button>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md">
                <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome ou usuário..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Todos os Cargos</option>
                    <option value="super" <?php echo (($_GET['role'] ?? '') == 'super') ? 'selected' : ''; ?>>Super Admin</option>
                    <option value="admin" <?php echo (($_GET['role'] ?? '') == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                    <option value="editor" <?php echo (($_GET['role'] ?? '') == 'editor') ? 'selected' : ''; ?>>Editor</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<?php consume_status(); ?>

<div class="row">
    <?php if (empty($users)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">Nenhum usuário encontrado.</div>
        </div>
    <?php else: ?>
        <?php foreach ($users as $user): ?>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card user-admin-card h-100">
                    <div class="card-body d-flex">
                        <div class="user-avatar me-3">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($user->name); ?></h5>
                            <p class="card-text text-muted mb-2">@<?php echo htmlspecialchars($user->username); ?></p>
                            <?php
                            $role_class = 'bg-secondary-soft text-secondary';
                            if ($user->auth_level == 'super') $role_class = 'bg-danger-soft text-danger';
                            if ($user->auth_level == 'admin') $role_class = 'bg-primary-soft text-primary';
                            ?>
                            <span class="badge <?php echo $role_class; ?>"><?php echo ucfirst($user->auth_level); ?></span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal"
                                        data-id="<?php echo $user->id; ?>"
                                        data-name="<?php echo htmlspecialchars($user->name); ?>"
                                        data-username="<?php echo htmlspecialchars($user->username); ?>"
                                        data-auth_level="<?php echo $user->auth_level; ?>">
                                        <i class="bi bi-pencil me-2"></i>Editar
                                    </a>
                                </li>
                                <?php if ($user->auth_level !== 'super'): ?>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteUserModal"
                                            data-id="<?php echo $user->id; ?>"
                                            data-name="<?php echo htmlspecialchars($user->name); ?>">
                                            <i class="bi bi-trash me-2"></i>Excluir
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<nav class="d-flex justify-content-center mt-4">
    <?php $admin_user_controller->display_pagination($total_items, $items_per_page); ?>
</nav>

<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalTitle">Novo Usuário Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="post" action="<?php echo ADMIN_URL; ?>users">
                <div class="modal-body">
                    <input type="hidden" name="action_type" id="userActionType" value="create">
                    <input type="hidden" name="user_id" id="editUserId">

                    <div class="mb-3">
                        <label for="editUserName" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="editUserName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserUsername" class="form-label">Nome de Usuário</label>
                        <input type="text" class="form-control" id="editUserUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserPassword" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="editUserPassword" name="password" placeholder="Deixe em branco para não alterar">
                        <small class="form-text text-muted">A senha deve ter no mínimo 6 caracteres.</small>
                    </div>
                    <div class="mb-3">
                        <label for="editUserAuthLevel" class="form-label">Cargo</label>
                        <select class="form-select" id="editUserAuthLevel" name="auth_level" required>
                            <option value="editor">Editor</option>
                            <option value="admin">Administrador</option>
                            <?php if (get_admin_user_role() === 'super'): ?>
                                <option value="super">Super Admin</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="saveUserBtn">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="deleteUserForm" method="post" action="<?php echo ADMIN_URL; ?>users">
                <div class="modal-body">
                    <input type="hidden" name="action_type" value="delete">
                    <input type="hidden" name="user_id" id="deleteUserId">
                    <p>Tem certeza que deseja excluir o usuário <strong id="userNameToDelete"></strong>?</p>
                    <p class="text-danger">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>