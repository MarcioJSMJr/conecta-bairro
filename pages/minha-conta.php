<section class="page-header-simple">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Minha Conta</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Início</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Minha Conta</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php consume_status(); ?>
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar">
                    <div class="profile-header text-center">
                        <i class="bi bi-person-circle display-3 text-primary"></i>
                        <h5 class="mt-2 mb-0"><?php echo htmlspecialchars($current_user->full_name); ?></h5>
                        <small class="text-muted"><?php echo htmlspecialchars($current_user->email); ?></small>
                    </div>
                    <div class="nav flex-column nav-pills mt-4" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active text-start" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab"><i class="bi bi-person-fill me-2"></i> Meu Perfil</button>
                        <button class="nav-link text-start" id="v-pills-donations-tab" data-bs-toggle="pill" data-bs-target="#v-pills-donations" type="button" role="tab"><i class="bi bi-box-seam-fill me-2"></i> Minhas Doações</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel">
                        <div class="account-content-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Informações do Perfil</h5>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal"><i class="bi bi-pencil-fill me-2"></i>Editar Perfil</button>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-3">Nome Completo</dt>
                                    <dd class="col-sm-9"><?php echo htmlspecialchars($current_user->full_name); ?></dd>

                                    <dt class="col-sm-3">E-mail</dt>
                                    <dd class="col-sm-9"><?php echo htmlspecialchars($current_user->email); ?></dd>

                                    <dt class="col-sm-3">Telefone</dt>
                                    <dd class="col-sm-9"><?php echo htmlspecialchars($current_user->phone_number ?? 'Não informado'); ?></dd>
                                </dl>
                                <hr>
                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><i class="bi bi-key-fill me-2"></i>Alterar Senha</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-donations" role="tabpanel">
                        <div class="account-content-card">
                            <div class="card-header">
                                <h5>Minhas Doações</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($user_donations)): ?>
                                    <p class="text-center">Você ainda não fez nenhuma doação.</p>
                                <?php else: ?>
                                    <div class="row">
                                        <?php foreach ($user_donations as $donation): ?>
                                            <div class="col-lg-6 mb-4">
                                                <div class="card donation-card-compact h-100">
                                                    <div class="card-body d-flex align-items-center">
                                                        <div class="item-image me-3">
                                                            <img src="<?php echo ADMIN_URL . $donation->image_url; ?>" alt="<?php echo htmlspecialchars($donation->title); ?>">
                                                        </div>

                                                        <div class="item-content-wrapper flex-grow-1">
                                                            <div class="item-info">
                                                                <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($donation->title); ?></h5>
                                                                <small class="text-muted"><?php echo htmlspecialchars($donation->category_name ?? 'N/A'); ?> &bull; <i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($donation->neighborhood); ?></small>
                                                            </div>
                                                            <div class="item-status mt-2">
                                                                <span class="badge bg-success-soft text-success"><?php echo htmlspecialchars($donation->status); ?></span>
                                                            </div>
                                                        </div>

                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-icon-only btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a class="dropdown-item" href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editDonationModal"
                                                                        data-id="<?php echo $donation->id; ?>"
                                                                        data-title="<?php echo htmlspecialchars($donation->title); ?>"
                                                                        data-description="<?php echo htmlspecialchars($donation->description); ?>"
                                                                        data-category_id="<?php echo $donation->category_id; ?>"
                                                                        data-condition="<?php echo $donation->condition; ?>"
                                                                        data-status="<?php echo $donation->status; ?>"
                                                                        data-neighborhood="<?php echo htmlspecialchars($donation->neighborhood); ?>"
                                                                        data-image_url="<?php echo ADMIN_URL . $donation->image_url; ?>">
                                                                        <i class="bi bi-pencil me-2"></i>Editar
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#confirmDeleteModal"
                                                                        data-id="<?php echo $donation->id; ?>"
                                                                        data-title="<?php echo htmlspecialchars($donation->title); ?>">
                                                                        <i class="bi bi-trash me-2"></i>Excluir
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Informações do Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="update_profile">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo htmlspecialchars($current_user->full_name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($current_user->email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Telefone</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($current_user->phone_number ?? ''); ?>">
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

<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alterar Senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="changePasswordForm">
                <input type="hidden" name="action" value="update_profile">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                        <div id="passwordError" class="invalid-feedback">As senhas não coincidem.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Nova Senha</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editDonationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Editar Doação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editDonationForm" method="post" action="<?php echo BASE_URL; ?>index.php" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_donation">
                    <input type="hidden" name="donation_id" id="editDonationId">

                    <div class="mb-3">
                        <label class="form-label">Imagem do Item</label>
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <img src="" id="editImagePreview" class="img-fluid rounded border" alt="Preview da imagem">
                            </div>
                            <div class="col-md-8">
                                <input class="form-control" type="file" id="editImageInput" name="image" accept="image/*">
                                <small class="form-text text-muted">Deixe em branco para manter a imagem atual.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="editTitle" class="form-label">Título do Item</label>
                            <input type="text" class="form-control" id="editTitle" name="title" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editCategoryId" class="form-label">Categoria</label>
                            <select class="form-select" id="editCategoryId" name="category_id" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>"><?php echo htmlspecialchars($category->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Descrição</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="4"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="editCondition" class="form-label">Condição</label>
                            <select class="form-select" id="editCondition" name="condition">
                                <option value="Usado">Usado</option>
                                <option value="Seminovo">Seminovo</option>
                                <option value="Novo">Novo</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status">
                                <option value="Disponível">Disponível</option>
                                <option value="Reservado">Reservado</option>
                                <option value="Doado">Doado</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editNeighborhood" class="form-label">Bairro para Retirada</label>
                            <input type="text" class="form-control" id="editNeighborhood" name="neighborhood" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="editDonationForm" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a doação <strong id="donationNameToDelete"></strong>?</p>
                <p class="text-danger">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteDonationForm" method="post" action="<?php echo BASE_URL; ?>index.php">
                    <input type="hidden" name="action" value="delete_donation">
                    <input type="hidden" name="donation_id" id="deleteDonationId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const passwordForm = document.getElementById('changePasswordForm');
    passwordForm.addEventListener('submit', function(event) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        if (password.value !== confirmPassword.value) {
            event.preventDefault();
            confirmPassword.classList.add('is-invalid');
        } else {
            confirmPassword.classList.remove('is-invalid');
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'donations') {
            const donationsTab = document.getElementById('v-pills-donations-tab');
            if (donationsTab) {
                new bootstrap.Tab(donationsTab).show();
            }
        }
    });
</script>