<div class="login-wrapper">
    <div class="login-card">
        <div class="logo-wrapper">
            <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo <?php echo SITE_NAME; ?>">
        </div>
        <h2>Acesso ao Painel</h2>
        <p class="text-muted">Faça login para gerenciar a plataforma.</p>

        <?php consume_status(); ?>

        <form method="post" action="<?php echo ADMIN_URL; ?>login">
            <div class="mb-3 form-group-icon">
                <i class="bi bi-person form-icon"></i>
                <input name="username" type="text" class="form-control" id="username" placeholder="Nome de Usuário" required>
            </div>
            <div class="mb-4 form-group-icon">
                <i class="bi bi-lock form-icon"></i>
                <input name="password" type="password" class="form-control" id="password" placeholder="Senha" required>
            </div>

            <div class="d-grid">
                <button class="btn btn-submit btn-lg" type="submit">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                </button>
            </div>

            <?php if (isset($show_register_link) && $show_register_link): ?>
                <div class="text-center mt-4">
                    <p class="text-muted">Primeiro acesso?
                        <a href="<?php echo ADMIN_URL; ?>register" class="text-primary fw-semibold"> Crie a conta do Super Admin </a>
                    </p>
                </div>
            <?php endif; ?>

        </form>
    </div>
</div>