<?php

if (!isset($donation)) {
    include 'pages/404.php';
    exit();
}

?>

<section class="page-header" style="background-image: url('<?php echo ADMIN_URL . htmlspecialchars($donation->image_url); ?>');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 data-aos="fade-up"><?php echo htmlspecialchars($donation->title); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" data-aos="fade-up" data-aos-delay="100">
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Início</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>doacoes">Itens para Doação</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($donation->title); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="item-detalhe-section py-5">
    <div class="container">
        <div class="row align-items-start g-5">

            <div class="col-lg-6" data-aos="fade-right">
                <div class="gallery-wrapper">
                    <div class="main-image shadow-lg">
                        <a href="<?php echo ADMIN_URL . htmlspecialchars($donation->image_url); ?>" class="glightbox">
                            <img src="<?php echo ADMIN_URL . htmlspecialchars($donation->image_url); ?>" alt="Imagem principal do item <?php echo htmlspecialchars($donation->title); ?>" id="main-item-image">
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="item-content-wrapper">
                    <div class="d-flex align-items-center mb-2">
                        <span class="post-category me-3"><i class="bi bi-tags-fill"></i> <?php echo htmlspecialchars($donation->category_name); ?></span>
                        <span class="badge bg-success-soft text-success"><?php echo htmlspecialchars($donation->status); ?></span>
                    </div>

                    <h2 class="item-title"><?php echo htmlspecialchars($donation->title); ?></h2>

                    <div class="item-lead-box my-4">
                        <p class="lead"><?php echo nl2br(htmlspecialchars($donation->description)); ?></p>
                    </div>

                    <button id="show-contact-btn" class="btn btn-primary rounded-pill fw-bold px-4 py-3 shadow-sm d-inline-flex align-items-center">
                        <i class="bi bi-heart-fill me-2"></i>Tenho Interesse
                    </button>

                    <div id="contact-info-box" class="contact-info-box mt-4" style="display: none;">
                        <h4>Combine a Retirada</h4>
                        <p>Para combinar a retirada, o item está disponível no seguinte local:</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-geo-alt-fill"></i> <strong>Bairro:</strong> <?php echo htmlspecialchars($donation->neighborhood); ?></li>
                            <li><i class="bi bi-info-circle-fill"></i> <strong>Observação:</strong> Entre em contato com o doador através do sistema para mais detalhes.</li>
                        </ul>
                    </div>

                    <div class="detalhes-container mt-5">
                        <h3 class="detalhes-title">Detalhes do Item</h3>
                        <div class="detalhes-grid">
                            <div class="detalhe-item">
                                <div class="icon-check"><i class="bi bi-check-lg"></i></div>
                                <p><strong>Condição:</strong> <?php echo htmlspecialchars($donation->condition); ?></p>
                            </div>
                            <div class="detalhe-item">
                                <div class="icon-check"><i class="bi bi-check-lg"></i></div>
                                <p><strong>Postado em:</strong> <?php echo $donation->created_at->format('d/m/Y'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('show-contact-btn').addEventListener('click', function() {
        const contactBox = document.getElementById('contact-info-box');
        this.style.display = 'none';
        contactBox.style.display = 'block';
    });
</script>