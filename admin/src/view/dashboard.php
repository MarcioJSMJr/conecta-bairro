<main class="p-4">
    <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
        <div>
            <h1 class="fw-bold">Bem-vindo, <?php echo htmlspecialchars($_SESSION[ADMIN_SESSION]['name'] ?? 'Admin'); ?>!</h1>
            <p class="text-muted mb-0">Aqui está um resumo da sua comunidade hoje, <?php
                                                                                    try {
                                                                                        $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'America/Sao_Paulo');
                                                                                        echo $formatter->format(time());
                                                                                    } catch (Exception $e) {
                                                                                        echo date('d \d\e F \d\e Y');
                                                                                    }
                                                                                    ?>.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up">
            <div class="card stat-card-v2 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted fw-normal">Doações Novas Hoje</h6>
                            <h2 class="fw-bold"><?php echo $donations_today; ?></h2>
                            <span class="badge bg-success-soft text-success">+2 vs ontem</span>
                        </div>
                        <div class="stat-icon icon-primary">
                            <i class="bi bi-gift-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card stat-card-v2 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted fw-normal">Total de Itens Doados</h6>
                            <h2 class="fw-bold"><?php echo $total_donations; ?></h2>
                            <span class="badge bg-info-soft text-info">Ativo</span>
                        </div>
                        <div class="stat-icon icon-info">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card stat-card-v2 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted fw-normal">Novos Usuários</h6>
                            <h2 class="fw-bold"><?php echo $users_today; ?></h2>
                            <span class="badge bg-success-soft text-success">+1 vs ontem</span>
                        </div>
                        <div class="stat-icon icon-success">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card stat-card-v2 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted fw-normal">Pontos de Coleta</h6>
                            <h2 class="fw-bold"><?php echo $total_collection_points; ?></h2>
                            <span class="badge bg-secondary-soft text-secondary">Cadastrados</span>
                        </div>
                        <div class="stat-icon icon-warning">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-lg-12 mb-4" data-aos="fade-up" data-aos-delay="400">
            <div class="card h-100">
                <div class="card-header">Atividade da Semana (Doações)</div>
                <div class="card-body">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>