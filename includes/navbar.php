<nav id="navbar" class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo <?php echo SITE_NAME; ?>" class="logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'home') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'doacoes' || $page == 'doacao-detalhe') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>doacoes">Itens para Doação</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'pontos-coleta') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>pontos-coleta">Pontos de Coleta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'sobre') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>sobre">Sobre o Projeto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'contato') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>contato">Contato</a>
                </li>
            </ul>

            <div class="d-flex ms-auto nav-btn-container align-items-center">
                <div class="ms-lg-3">
                    <?php if (is_site_user_logged_in()) : ?>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-2 fs-5"></i>
                                <span class="d-none d-lg-inline"><?php echo explode(' ', $_SESSION[SITE_USER_SESSION]['full_name'])[0]; ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>minha-conta">Meus Dados</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newDonationModal">Nova Doação</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout.php">Sair</a></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo BASE_URL; ?>login" class="btn btn-outline-primary d-flex align-items-center">
                            <i class="bi bi-person-circle me-2"></i> Entrar
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</nav>