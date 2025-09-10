<section id="inicio" class="hero-section d-flex flex-column justify-content-center align-items-center text-center mt-5" style="background-image: url('<?php echo BASE_URL; ?>assets/images/hero-background.png');">
    <div class="hero-overlay"></div>
    <div class="hero-content" data-aos="fade-up">
        <h1 class="display-3 fw-bold text-white"><?php echo SITE_NAME; ?></h1>
        <p class="lead text-white">Transformando itens parados em oportunidades. <br> Doe, recicle e fortaleça nossa comunidade.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
            <a href="<?php echo BASE_URL; ?>doacoes" class="btn btn-primary btn-lg">Ver Itens para Doação</a>
            <a href="#como-funciona" class="btn btn-secondary btn-lg">
                <i class="bi bi-patch-question-fill me-2"></i> Como Participar?
            </a>
        </div>
    </div>
</section>

<section id="sobre-projeto" class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <img src="<?php echo BASE_URL; ?>assets/images/sobre-projeto.png" class="img-fluid rounded shadow-lg" alt="Comunidade unida pelo projeto <?php echo SITE_NAME; ?>">
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="section-header text-start">
                    <h2 class="section-title">Nossa Missão: Conectar e Transformar</h2>
                    <p>O <?php echo SITE_NAME; ?> nasceu da ideia de que juntos podemos criar um ciclo virtuoso em nossa cidade. Facilitamos a doação de objetos e a reciclagem, combatendo o desperdício e ajudando quem mais precisa.</p>
                </div>
                <ul class="list-unstyled mt-4">
                    <li class="d-flex align-items-start mb-3" data-aos="fade-up" data-aos-delay="100">
                        <i class="bi bi-recycle fs-4 me-3"></i>
                        <div>
                            <h5>Consumo Consciente</h5>
                            <p>Incentivamos a reutilização de produtos, estendendo sua vida útil e reduzindo o impacto ambiental.</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start mb-3" data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-people-fill fs-4 me-3"></i>
                        <div>
                            <h5>Fortalecimento Comunitário</h5>
                            <p>Criamos uma ponte solidária entre vizinhos, fortalecendo os laços e o apoio mútuo em nossa comunidade.</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start" data-aos="fade-up" data-aos-delay="300">
                        <i class="bi bi-graph-up-arrow fs-4 me-3"></i>
                        <div>
                            <h5>Impacto Social Positivo</h5>
                            <p>Itens que seriam descartados encontram um novo lar, ajudando famílias e instituições locais.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="como-funciona" class="py-5">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">É Simples, Rápido e Gratuito</h2>
            <p>Veja como é fácil participar e fazer a diferença.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card text-center p-4 h-100">
                    <div class="icon-box mb-4"><i class="bi bi-box-arrow-up"></i></div>
                    <h3>1. Cadastre seu Item</h3>
                    <p>Tire uma foto do objeto que você não usa mais, descreva-o e publique em nossa plataforma. É rápido e intuitivo.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card text-center p-4 h-100">
                    <div class="icon-box mb-4"><i class="bi bi-search"></i></div>
                    <h3>2. Encontre o que Precisa</h3>
                    <p>Navegue pelas categorias e encontre móveis, eletrônicos e outros itens doados por pessoas perto de você.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card text-center p-4 h-100">
                    <div class="icon-box mb-4"><i class="bi bi-person-check-fill"></i></div>
                    <h3>3. Combine a Retirada</h3>
                    <p>Entre em contato diretamente com o doador através da plataforma para combinar a melhor forma de retirar o item.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="doacoes-destaque" class="py-5">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Últimas Doações Adicionadas</h2>
            <p>Estes são alguns dos itens que acabaram de ser disponibilizados pela comunidade.</p>
        </div>
        <div class="row g-4">
            <?php if (empty($latest_donations)) : ?>
                <div class="col-12">
                    <p class="text-center">Nenhuma doação cadastrada no momento.</p>
                </div>
            <?php else : ?>
                <?php foreach ($latest_donations as $index => $donation) : ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                        <div class="doacao-card h-100">
                            <div class="doacao-card-img">
                                <a href="<?php echo BASE_URL . 'doacao/' . $donation->slug; ?>">
                                    <img src="<?php echo ADMIN_URL . htmlspecialchars($donation->image_url); ?>" alt="<?php echo htmlspecialchars($donation->title); ?>" class="img-fluid">
                                </a>
                            </div>
                            <div class="doacao-card-body">
                                <div class="card-meta mb-2">
                                    <span class="post-category"><i class="bi bi-tags-fill"></i> <?php echo htmlspecialchars($donation->category_name); ?></span>
                                </div>
                                <h3 class="card-title">
                                    <a href="<?php echo BASE_URL . 'doacao/' . $donation->slug; ?>"><?php echo htmlspecialchars($donation->title); ?></a>
                                </h3>
                                <p class="card-text"><?php echo htmlspecialchars($donation->description); ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="<?php echo BASE_URL . 'doacao/' . $donation->slug; ?>" class="read-more">Ver Detalhes <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="pontos-coleta" class="py-5">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Pontos de Coleta Seletiva</h2>
            <p>Encontre o local mais próximo para descartar seus recicláveis corretamente.</p>
        </div>
        <div class="row g-4">
            <?php if (empty($featured_collection_points)) : ?>
                <div class="col-12">
                    <p class="text-center">Nenhum ponto de coleta cadastrado no momento.</p>
                </div>
            <?php else : ?>
                <?php
                $point_icons = [
                    'Geral' => 'bi-recycle',
                    'Eletrônicos' => 'bi-pc-display',
                    'Óleo' => 'bi-droplet-fill',
                    'Pilhas e Baterias' => 'bi-battery-charging'
                ];
                ?>
                <?php foreach ($featured_collection_points as $index => $point) : ?>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                        <div class="coleta-card text-center p-4 h-100">
                            <div class="icon-box-coleta mb-4"><i class="bi <?php echo $point_icons[$point->category] ?? 'bi-geo-alt-fill'; ?>"></i></div>
                            <h3 class="h5"><?php echo htmlspecialchars($point->name); ?></h3>
                            <p class="text-muted"><?php echo htmlspecialchars($point->category); ?></p>
                            <p class="mb-3"><?php echo htmlspecialchars($point->getFullAddress()); ?></p>
                            <a href="<?php echo htmlspecialchars($point->Maps_link); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">Ver no Mapa</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section id="cta-participar" class="cta-section py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8" data-aos="fade-up">
                <i class="bi bi-heart-fill display-1 text-danger mb-3"></i>
                <h2 class="text-white display-5 fw-bold">Faça Parte da Mudança!</h2>
                <p class="text-white lead mb-4">Sua doação pode transformar a vida de alguém. Um objeto parado na sua casa pode ser a solução que uma família precisa. Participe agora mesmo!</p>
                <a href="<?php echo BASE_URL; ?>login" class="btn btn-primary btn-lg rounded-pill px-5 py-3">
                    <i class="bi bi-gift-fill me-2"></i> Quero Doar Agora
                </a>
            </div>
        </div>
    </div>
</section>

<section id="localizacao" class="py-5">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Nossa Área de Atuação</h2>
            <p>O projeto está focado em conectar os moradores de Itapetininga - SP.</p>
        </div>
        <div class="row g-4">
            <div class="col-12" data-aos="fade-up">
                <div class="map-box w-100 h-100">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7313.2998219329265!2d-48.02805875!3d-23.58101385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c5cc69213fcebf%3A0xad779cf70ea07a37!2sJardim%20Paulista%2C%20Itapetininga%20-%20SP!5e0!3m2!1spt-BR!2sbr!4v1755711395827!5m2!1spt-BR!2sbr"
                        width="100%" height="450" style="border:0; border-radius: var(--border-radius);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>