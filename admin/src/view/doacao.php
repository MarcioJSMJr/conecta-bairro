<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Gerenciar Doações</h1>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md">
                <input type="text" name="search" class="form-control" placeholder="Pesquisar por título..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">Todas as Categorias</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo (($_GET['category_id'] ?? '') == $category->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->name); ?>
                        </option>
                    <?php endforeach; ?>
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
    <?php if (empty($donations)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">Nenhuma doação encontrada com os filtros aplicados.</div>
        </div>
    <?php else: ?>
        <?php foreach ($donations as $donation): ?>
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
    <?php endif; ?>
</div>

<nav class="d-flex justify-content-center mt-4">
    <?php $donation_controller->display_pagination($total_items, $items_per_page); ?>
</nav>

<div class="modal fade" id="editDonationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Editar Doação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editDonationForm" method="post" action="<?php echo ADMIN_URL; ?>donations" enctype="multipart/form-data">
                    <input type="hidden" name="action_type" value="edit">
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
                <form id="deleteDonationForm" method="post" action="<?php echo ADMIN_URL; ?>donations">
                    <input type="hidden" name="action_type" value="delete">
                    <input type="hidden" name="donation_id" id="deleteDonationId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>