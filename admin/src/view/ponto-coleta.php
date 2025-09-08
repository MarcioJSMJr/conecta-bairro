<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Gerenciar Pontos de Coleta</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPointModal">
        <i class="bi bi-plus-circle me-2"></i>Novo Ponto
    </button>
</div>

<div class="card search-bar-card mb-4">
    <div class="card-body p-0">
        <form method="GET" class="d-flex search-form">
            <input type="text" name="search" class="form-control me-2" placeholder="Pesquisar por nome do local..." value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
            <button type="submit" class="btn btn-primary d-flex align-items-center"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<?php consume_status(); ?>

<div class="row">
    <?php if (empty($points)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center">Nenhum ponto de coleta encontrado.</div>
        </div>
    <?php else: ?>
        <?php foreach ($points as $point): ?>
            <div class="col-12 col-lg-6 mb-4">
                <div class="card point-admin-card h-100">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="icon-wrapper me-3">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($point->name); ?></h5>
                                    <span class="badge bg-secondary-soft text-secondary"><?php echo htmlspecialchars($point->category); ?></span>
                                </div>
                                <p class="text-muted small mb-2"><?php echo htmlspecialchars($point->getFullAddress()); ?></p>
                                <p class="small mb-0"><strong>Materiais:</strong> <?php echo htmlspecialchars($point->accepted_materials); ?></p>
                            </div>
                            <div class="dropdown ms-3">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPointModal"
                                            data-id="<?php echo $point->id; ?>"
                                            data-name="<?php echo htmlspecialchars($point->name); ?>"
                                            data-street="<?php echo htmlspecialchars($point->street); ?>"
                                            data-number="<?php echo htmlspecialchars($point->number ?? ''); ?>"
                                            data-neighborhood="<?php echo htmlspecialchars($point->neighborhood ?? ''); ?>"
                                            data-city="<?php echo htmlspecialchars($point->city); ?>"
                                            data-state="<?php echo htmlspecialchars($point->state); ?>"
                                            data-materials="<?php echo htmlspecialchars($point->accepted_materials); ?>"
                                            data-category="<?php echo $point->category; ?>">
                                            <i class="bi bi-pencil me-2"></i>Editar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmDeletePointModal"
                                            data-id="<?php echo $point->id; ?>"
                                            data-name="<?php echo htmlspecialchars($point->name); ?>">
                                            <i class="bi bi-trash me-2"></i>Excluir
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<nav class="d-flex justify-content-center mt-4">
    <?php $collection_point_controller->display_pagination($total_items, $items_per_page); ?>
</nav>

<div class="modal fade" id="editPointModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPointModalTitle">Novo Ponto de Coleta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPointForm" method="post" action="<?php echo ADMIN_URL; ?>collection-points">
                <div class="modal-body">
                    <input type="hidden" name="action_type" id="pointActionType" value="create">
                    <input type="hidden" name="point_id" id="editPointId">

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="editPointName" class="form-label">Nome do Local</label>
                            <input type="text" class="form-control" id="editPointName" name="name" required placeholder="Ex: Ecoponto Municipal">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editPointCategory" class="form-label">Tipo Principal</label>
                            <select class="form-select" id="editPointCategory" name="category" required>
                                <option value="Geral">Geral</option>
                                <option value="Eletrônicos">Eletrônicos</option>
                                <option value="Óleo">Óleo</option>
                                <option value="Pilhas e Baterias">Pilhas e Baterias</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editPointStreet" class="form-label">Rua</label>
                        <input type="text" class="form-control" id="editPointStreet" name="street" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="editPointNumber" class="form-label">Número</label>
                            <input type="text" class="form-control" id="editPointNumber" name="number">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="editPointNeighborhood" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="editPointNeighborhood" name="neighborhood" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="editPointCity" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="editPointCity" name="city" required value="Itapetininga">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editPointState" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="editPointState" name="state" required value="SP">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editPointMaterials" class="form-label">Materiais Aceitos (separados por vírgula)</label>
                        <input type="text" class="form-control" id="editPointMaterials" name="accepted_materials" placeholder="Ex: Papel, Plástico, Vidro, Pneus">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="savePointBtn">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeletePointModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="deletePointForm" method="post" action="<?php echo ADMIN_URL; ?>collection-points">
                <div class="modal-body">
                    <input type="hidden" name="action_type" value="delete">
                    <input type="hidden" name="point_id" id="deletePointId">
                    <p>Tem certeza que deseja excluir o ponto de coleta <strong id="pointNameToDelete"></strong>?</p>
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