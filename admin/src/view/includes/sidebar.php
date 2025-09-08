<?php

$user_role = get_admin_user_role();

$managementPages = ['users', 'site-users', 'logs', 'settings'];
$isManagementPage = in_array($resource, $managementPages);

?>

<div class="sidebar-wrapper" id="sidebar-wrapper">
    <div class="sidebar-header">
        <a href="<?php echo ADMIN_URL; ?>dashboard" class="sidebar-brand">
            <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo" class="logo">
        </a>
        <button class="btn sidebar-close-btn d-lg-none sidebar-toggle-btn">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <ul class="sidebar-nav">
        <li class="sidebar-item <?php echo ($resource === 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo ADMIN_URL; ?>dashboard" class="sidebar-link">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="my-2">

        <?php if (in_array($user_role, [ROLE_EDITOR, ROLE_ADMIN, ROLE_SUPER_ADMIN])) : ?>
            <li class="sidebar-item <?php echo ($resource === 'donations') ? 'active' : ''; ?>">
                <a href="<?php echo ADMIN_URL; ?>donations" class="sidebar-link">
                    <i class="bi bi-box-seam-fill"></i>
                    <span>Doações</span>
                </a>
            </li>
            <li class="sidebar-item <?php echo ($resource === 'donation-categories') ? 'active' : ''; ?>">
                <a href="<?php echo ADMIN_URL; ?>donation-categories" class="sidebar-link">
                    <i class="bi bi-tags-fill"></i>
                    <span>Categorias</span>
                </a>
            </li>
            <li class="sidebar-item <?php echo ($resource === 'collection-points') ? 'active' : ''; ?>">
                <a href="<?php echo ADMIN_URL; ?>collection-points" class="sidebar-link">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Pontos de Coleta</span>
                </a>
            </li>
        <?php endif; ?>


        <?php if (in_array($user_role, [ROLE_ADMIN, ROLE_SUPER_ADMIN])) : ?>
            <hr class="my-2">

            <li class="sidebar-item">
                <a class="sidebar-link d-flex justify-content-between" data-bs-toggle="collapse" href="#submenu-management" role="button" aria-expanded="<?php echo $isManagementPage ? 'true' : 'false'; ?>">
                    <span>
                        <i class="bi bi-gear-fill"></i>
                        <span>Gerenciamento</span>
                    </span>
                    <i class="bi bi-chevron-down small"></i>
                </a>
                <div class="collapse <?php echo $isManagementPage ? 'show' : ''; ?>" id="submenu-management">
                    <ul class="nav flex-column ps-4">
                        <li class="sidebar-item <?php echo ($resource === 'users') ? 'active' : ''; ?>">
                            <a href="<?php echo ADMIN_URL; ?>users" class="sidebar-link">
                                <i class="bi bi-shield-lock-fill"></i> <span>Usuários Admin</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?php echo ($resource === 'site-users') ? 'active' : ''; ?>">
                            <a href="<?php echo ADMIN_URL; ?>site-users" class="sidebar-link">
                                <i class="bi bi-people-fill"></i> <span>Usuários do Site</span>
                            </a>
                        </li>

                        <?php if ($user_role === ROLE_SUPER_ADMIN) : ?>
                            <li class="sidebar-item <?php echo ($resource === 'logs') ? 'active' : ''; ?>">
                                <a href="<?php echo ADMIN_URL; ?>logs" class="sidebar-link">
                                    <i class="bi bi-clipboard2-data-fill"></i> <span>Logs de Atividade</span>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</div>