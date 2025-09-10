<?php

$donations = $donations_result['items'];
$total_items = $donations_result['total'];

?>

<section class="page-header" style="background-image: url('<?php echo BASE_URL; ?>assets/images/hero-background.png');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <h1 class="page-title display-4 text-white">Itens para Doação</h1>
                <p class="page-subtitle lead text-white-50 mb-4">Encontre aqui o que você precisa! Itens doados com carinho pela nossa comunidade para ajudar quem mais precisa.</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>" class="text-white opacity-75">Início</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Doações</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section id="doacoes-galeria" class="py-5">
    <div class="container">

        <div class="row mb-4" data-aos="fade-up">
            <div class="col-md-8 offset-md-2">
                <form method="GET" class="filter-bar d-flex align-items-center p-3 rounded shadow-sm">
                    <div class="flex-grow-1 me-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Pesquisar por um item..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    <div class="me-2" style="width: 220px;">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">Todas as Categorias</option>
                            <?php foreach ($all_categories as $category) : ?>
                                <option value="<?php echo $category->id; ?>" <?php echo (($_GET['category_id'] ?? '') == $category->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center"><i class="bi bi-search me-1"></i>Buscar</button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <?php if (empty($donations)) : ?>
                <div class="col-12 text-center" data-aos="fade-up">
                    <div class="alert alert-info p-5">
                        <i class="bi bi-info-circle-fill display-4 d-block mb-3"></i>
                        <h4 class="alert-heading">Nenhum item encontrado!</h4>
                        <p>Tente ajustar os filtros de busca ou volte mais tarde. Novos itens são adicionados todos os dias!</p>
                    </div>
                </div>
            <?php else : ?>
                <?php foreach ($donations as $index => $donation) : ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3 + 1) * 100; ?>">
                        <div class="doacao-card h-100">
                            <div class="doacao-card-img">
                                <a href="<?php echo BASE_URL . 'doacao/' . $donation->slug; ?>">
                                    <img src="<?php echo ADMIN_URL . htmlspecialchars($donation->image_url); ?>" alt="<?php echo htmlspecialchars($donation->title); ?>" class="img-fluid">
                                </a>
                            </div>
                            <div class="doacao-card-body">
                                <?php if (!empty($donation->category_name)) : ?>
                                    <div class="card-meta mb-2">
                                        <span class="post-category"><i class="bi bi-tags-fill"></i> <?php echo htmlspecialchars($donation->category_name); ?></span>
                                    </div>
                                <?php endif; ?>
                                <h3 class="card-title">
                                    <a href="<?php echo BASE_URL . 'doacao/' . $donation->slug; ?>"><?php echo htmlspecialchars($donation->title); ?></a>
                                </h3>
                                <p class="card-text"><?php echo substr(strip_tags($donation->description), 0, 80) . '...'; ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo BASE_URL . 'doacao/' . $donation->slug; ?>" class="read-more">Ver Detalhes <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <nav class="mt-5" aria-label="Paginação de doações" data-aos="fade-up">
            <?php $donation_controller->display_pagination($total_items, $items_per_page); ?>
        </nav>

    </div>
</section>