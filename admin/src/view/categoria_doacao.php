<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Gerenciar Categorias</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal">
        <i class="bi bi-plus-circle me-2"></i>Nova Categoria
    </button>
</div>

<div class="card search-bar-card mb-4">
    <div class="card-body p-0">
        <form method="GET" class="d-flex search-form">
            <input type="text" name="search" class="form-control me-2" placeholder="Pesquisar por nome..." value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" class="btn btn-primary d-flex align-items-center"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<?php consume_status(); ?>

<div class="row">
    <?php if (empty($categories)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <?php if (!empty($search_term)): ?>
                    Nenhuma categoria encontrada para "<?php echo htmlspecialchars($search_term); ?>".
                <?php else: ?>
                    Nenhuma categoria cadastrada.
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($categories as $category): ?>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card category-admin-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <i class="bi bi-tags-fill"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($category->name); ?></h5>
                        </div>

                        <div class="dropup">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal"
                                        data-id="<?php echo $category->id; ?>"
                                        data-name="<?php echo htmlspecialchars($category->name); ?>">
                                        <i class="bi bi-pencil me-2"></i>Editar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteCategoryModal"
                                        data-id="<?php echo $category->id; ?>"
                                        data-name="<?php echo htmlspecialchars($category->name); ?>">
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
    <?php $donation_category_controller->display_pagination($total_items, $items_per_page); ?>
</nav>


<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalTitle">Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" method="post" action="<?php echo ADMIN_URL; ?>donation-categories">
                    <input type="hidden" name="action_type" id="categoryActionType" value="create">
                    <input type="hidden" name="category_id" id="editCategoryId">

                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Nome da Categoria</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required placeholder="Ex: Móveis, Eletrônicos...">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="editCategoryForm" class="btn btn-primary" id="saveCategoryBtn">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a categoria <strong id="categoryNameToDelete"></strong>?</p>
                <p class="text-danger">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteCategoryForm" method="post" action="<?php echo ADMIN_URL; ?>donation-categories">
                    <input type="hidden" name="action_type" value="delete">
                    <input type="hidden" name="category_id" id="deleteCategoryId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>