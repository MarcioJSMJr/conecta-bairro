<div class="main-content-area">
    <section class="py-5">
        <div class="container">
            <div class="auth-card">
                <div class="auth-header text-center mb-4">
                    <a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo" style="max-height: 50px;"></a>
                    <h2 class="auth-title mt-3">Bem-vindo de volta!</h2>
                </div>

                <?php consume_status(); ?>

                <form method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="mb-3 form-group-icon">
                        <i class="bi bi-envelope-fill form-icon"></i>
                        <input type="email" class="form-control auth-input" name="email" id="email" placeholder="Seu e-mail" required>
                    </div>
                    <div class="mb-4 form-group-icon">
                        <i class="bi bi-key-fill form-icon"></i>
                        <input type="password" class="form-control auth-input" name="password" id="password" placeholder="Sua senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 auth-btn">Entrar</button>
                </form>
                <p class="text-center mt-4 auth-link-text">Não tem uma conta? <a href="<?php echo BASE_URL; ?>register" class="auth-link">Cadastre-se</a></p>
            </div>
        </div>
    </section>
</div>