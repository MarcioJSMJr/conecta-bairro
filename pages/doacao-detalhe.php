<?php

if (!isset($donation)) {
    include 'pages/404.php';
    exit();
}

$whatsapp_number = '';
if (!empty($donation->user_phone_number)) {
    $whatsapp_number = '55' . preg_replace('/\D/', '', $donation->user_phone_number);
}

?>

<section class="item-detalhe-section py-5 mt-5">
    <div class="container">
        <div class="row g-lg-5">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="gallery-wrapper">
                    <a href="<?php echo ADMIN_URL . htmlspecialchars($donation->image_url); ?>" class="glightbox main-image">
                        <img src="<?php echo ADMIN_URL . htmlspecialchars($donation->image_url); ?>" alt="Imagem principal do item <?php echo htmlspecialchars($donation->title); ?>">
                    </a>
                </div>
            </div>

            <div class="col-lg-5" data-aos="fade-left">
                <div class="item-info-card">
                    <div class="d-flex align-items-center item-meta">
                        <span class="post-category"><?php echo htmlspecialchars($donation->category_name); ?></span>
                        <span class="badge bg-success-soft text-success item-status"><?php echo htmlspecialchars($donation->status); ?></span>
                    </div>

                    <h2 class="item-title"><?php echo htmlspecialchars($donation->title); ?></h2>

                    <p class="item-description"><?php echo nl2br(htmlspecialchars($donation->description)); ?></p>

                    <button id="show-contact-btn" class="btn btn-primary rounded-pill fw-bold px-4 py-2 shadow-sm d-inline-flex align-items-center">
                        <i class="bi bi-heart-fill me-2"></i>Tenho Interesse
                    </button>

                    <div id="contact-info-box" class="contact-info-box">
                        <h4>Combine a Retirada</h4>
                        <ul class="list-unstyled mb-0">
                            <li><i class="bi bi-geo-alt-fill"></i> <strong>Bairro:</strong> <?php echo htmlspecialchars($donation->neighborhood); ?></li>
                            <?php if ($whatsapp_number): ?>
                                <li><i class="bi bi-info-circle-fill"></i> <strong>Observação:</strong> Clique no botão abaixo para conversar com o doador.</li>
                                <a href="https://wa.me/<?php echo $whatsapp_number; ?>?text=Ol%C3%A1%21+Tenho+interesse+no+item+%27<?php echo urlencode($donation->title); ?>%27+que+vi+no+Conecta-Bairro." target="_blank" class="btn-whatsapp-contact">
                                    <i class="bi bi-whatsapp"></i> Chamar no WhatsApp
                                </a>
                            <?php else: ?>
                                <li><i class="bi bi-info-circle-fill"></i> <strong>Observação:</strong> Entre em contato com o doador através do sistema para mais detalhes.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="item-details-card">
                    <h3 class="details-title">Detalhes do Item</h3>
                    <div class="details-grid">
                        <div class="detalhe-item">
                            <div class="icon-check"><i class="bi bi-shield-check"></i></div>
                            <p><strong>Condição:</strong> <?php echo htmlspecialchars($donation->condition); ?></p>
                        </div>
                        <div class="detalhe-item">
                            <div class="icon-check"><i class="bi bi-calendar-check"></i></div>
                            <p><strong>Postado em:</strong> <?php echo $donation->created_at->format('d/m/Y'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showContactBtn = document.getElementById('show-contact-btn');
        if (showContactBtn) {
            showContactBtn.addEventListener('click', function() {
                const contactBox = document.getElementById('contact-info-box');
                this.style.display = 'none';
                contactBox.style.display = 'block';
            });
        }
    });
</script>