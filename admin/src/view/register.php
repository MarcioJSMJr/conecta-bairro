<div class="login-wrapper">
    <div class="login-card">
        <div class="logo-wrapper">
            <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo <?php echo SITE_NAME; ?>">
        </div>
        <h2>Registro do Super Admin</h2>
        <p class="text-muted">Crie a primeira conta de administrador do sistema.</p>

        <?php consume_status(); ?>

        <form method="post" action="<?php echo ADMIN_URL; ?>register-super-admin">
            <input type="hidden" name="auth_level" value="super">

            <div class="mb-3 form-group-icon">
                <i class="bi bi-person-badge form-icon"></i>
                <input name="name" type="text" class="form-control" id="name" placeholder="Nome Completo" required>
            </div>
            <div class="mb-3 form-group-icon">
                <i class="bi bi-person form-icon"></i>
                <input name="username" type="text" class="form-control" id="username" placeholder="Nome de Usuário" required>
            </div>
            <div class="mb-3 form-group-icon">
                <i class="bi bi-lock form-icon"></i>
                <input name="password" type="password" class="form-control" id="password" placeholder="Crie uma senha forte" required>
            </div>
            <div class="d-grid mb-3">
                <button class="btn btn-submit btn-lg" type="submit">
                    <i class="bi bi-check-circle me-2"></i>Criar Conta
                </button>
            </div>
            <div class="text-center">
                <a href="<?php echo ADMIN_URL; ?>login" class="text-muted">Voltar para o Login</a>
            </div>
        </form>
    </div>
</div>