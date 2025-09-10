<section class="page-header" style="background-image: url('<?php echo BASE_URL; ?>assets/images/hero-background.png');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <h1 class="page-title display-4 text-white">Pontos de Coleta</h1>
                <p class="page-subtitle lead text-white-50 mb-4">Descarte corretamente e ajude o meio ambiente. <br> Encontre o local mais próximo de você.</p>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Início</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pontos de Coleta</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section id="lista-pontos-coleta" class="py-5">
    <div class="container">

        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">Onde Descartar?</h2>
            <p>Navegue pelas categorias para encontrar o ponto de coleta ideal para o seu resíduo.</p>
        </div>

        <?php if (empty($points_by_category)) : ?>
            <div class="alert alert-info text-center">Nenhum ponto de coleta cadastrado no momento.</div>
        <?php else : ?>

            <ul class="nav nav-tabs justify-content-center mb-4" id="coletaTabs" role="tablist" data-aos="fade-up">
                <?php $is_first_tab = true; ?>
                <?php foreach ($points_by_category as $category_name => $points) : ?>
                    <?php $category_slug = create_slug($category_name); ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php if ($is_first_tab) echo 'active'; ?>" id="<?php echo $category_slug; ?>-tab" data-bs-toggle="tab" data-bs-target="#<?php echo $category_slug; ?>" type="button" role="tab" aria-controls="<?php echo $category_slug; ?>" aria-selected="<?php echo $is_first_tab ? 'true' : 'false'; ?>">
                            <?php echo htmlspecialchars($category_name); ?>
                        </button>
                    </li>
                    <?php $is_first_tab = false; ?>
                <?php endforeach; ?>
            </ul>

            <div class="tab-content" id="coletaTabsContent" data-aos="fade-up" data-aos-delay="200">
                <?php
                $point_icons = [
                    'Geral' => 'bi-recycle',
                    'Eletrônicos' => 'bi-pc-display',
                    'Óleo' => 'bi-droplet-fill',
                    'Óleo de Cozinha' => 'bi-droplet-fill',
                    'Pilhas e Baterias' => 'bi-battery-charging'
                ];
                $is_first_pane = true;
                ?>
                <?php foreach ($points_by_category as $category_name => $points) : ?>
                    <?php $category_slug = create_slug($category_name); ?>
                    <div class="tab-pane fade <?php if ($is_first_pane) echo 'show active'; ?>" id="<?php echo $category_slug; ?>" role="tabpanel" aria-labelledby="<?php echo $category_slug; ?>-tab">
                        <?php foreach ($points as $point) : ?>
                            <div class="coleta-card-page">
                                <div class="icon"><i class="bi <?php echo $point_icons[$point->category] ?? 'bi-geo-alt-fill'; ?>"></i></div>
                                <div class="info">
                                    <h3 class="title"><?php echo htmlspecialchars($point->name); ?></h3>
                                    <p class="address"><?php echo htmlspecialchars($point->getFullAddress()); ?></p>
                                    <div class="materials">
                                        <?php
                                        $materials = explode(',', $point->accepted_materials);
                                        foreach ($materials as $material) {
                                            echo '<span>' . htmlspecialchars(trim($material)) . '</span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php if ($point->Maps_link) : ?>
                                    <div class="map-link">
                                        <a href="<?php echo htmlspecialchars($point->Maps_link); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">Ver no Mapa</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php $is_first_pane = false; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>