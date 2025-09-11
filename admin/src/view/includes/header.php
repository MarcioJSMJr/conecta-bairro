<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid justify-content-between">
        <button class="btn btn-outline-secondary sidebar-toggle-btn" id="sidebar-toggle">
            <i class="bi bi-list"></i>
        </button>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <span><?php echo htmlspecialchars($_SESSION[ADMIN_SESSION]['name'] ?? 'Admin'); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item text-danger" href="<?php echo ADMIN_URL; ?>logout.php">Sair</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>