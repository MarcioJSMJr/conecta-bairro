<footer id="footer" class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-4">

                <div class="col-lg-3 col-md-6 footer-info" data-aos="fade-up" data-aos-delay="100">
                    <a href="<?php echo BASE_URL; ?>" class="logo d-flex align-items-center mb-3">
                        <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Logo <?php echo SITE_NAME; ?>">
                    </a>
                    <p>Promovendo a sustentabilidade e a solidariedade através da tecnologia, conectando pessoas para um futuro melhor.</p>
                </div>

                <div class="col-lg-3 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="200">
                    <h4>Navegação</h4>
                    <ul>
                        <li><i class="fas fa-chevron-right"></i> <a href="<?php echo BASE_URL; ?>">Início</a></li>
                        <li><i class="fas fa-chevron-right"></i> <a href="<?php echo BASE_URL; ?>doacoes">Itens para Doação</a></li>
                        <li><i class="fas fa-chevron-right"></i> <a href="<?php echo BASE_URL; ?>sobre">Sobre o Projeto</a></li>
                        <li><i class="fas fa-chevron-right"></i> <a href="<?php echo BASE_URL; ?>contato">Contato</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="300">
                    <h4>Últimas Doações</h4>
                    <ul>
                        <?php
                        $footer_donations = $donation_controller->list(4);
                        if (empty($footer_donations)) :
                        ?>
                            <li><a href="#">Nenhuma doação recente</a></li>
                        <?php else : ?>
                            <?php foreach ($footer_donations as $donation) : ?>
                                <li>
                                    <i class="fas fa-chevron-right"></i>
                                    <a href="<?php echo BASE_URL . 'doacao/' . $donation->slug; ?>">
                                        <?php echo htmlspecialchars($donation->title); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-contact" data-aos="fade-up" data-aos-delay="400">
                    <h4>Entre em Contato</h4>
                    <p>
                        <strong><i class="bi bi-geo-alt-fill"></i></strong> <?php echo CONTACT_ADDRESS; ?><br>
                        <strong><i class="bi bi-telephone-fill"></i></strong> <?php echo CONTACT_PHONE; ?><br>
                        <strong><i class="bi bi-envelope-fill"></i></strong> <?php echo CONTACT_EMAIL; ?><br>
                    </p>
                    <div class="social-links mt-4">
                        <a href="<?php echo SOCIAL_FACEBOOK; ?>" class="facebook" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="<?php echo SOCIAL_INSTAGRAM; ?>" class="instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="copyright">
            Copyright &copy; <strong><?php echo SITE_NAME; ?></strong> <?php echo date("Y"); ?>. Todos os Direitos Reservados.
        </div>
        <div class="credits">
            Desenvolvido por: <a href="https://github.com/MarcioJSMJr">Marcio José</a>
        </div>
    </div>

</footer>

<a href="https://wa.me/<?php echo CONTACT_WHATSAPP_NUMBER; ?>?text=<?php echo CONTACT_WHATSAPP_MESSAGE; ?>" class="whatsapp-flutuante" target="_blank" title="Fale Conosco pelo WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<div id="cookie-consent-banner" class="cookie-consent-banner">
    <div class="cookie-consent-content">
        <p class="cookie-consent-text">
            Utilizamos cookies para melhorar sua experiência de navegação em nosso site. Ao continuar, você concorda com o uso de cookies.
            <a href="<?php echo BASE_URL; ?>politica-de-privacidade" class="cookie-consent-link">Saiba mais</a>.
        </p>
        <button id="accept-cookie-consent" class="cookie-consent-button">Aceitar</button>
    </div>
</div>

<div class="modal fade" id="newDonationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Nova Doação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="newDonationForm" method="post" action="<?php echo BASE_URL; ?>index.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="create_donation">

                    <div class="mb-3">
                        <label class="form-label">Imagem do Item</label>
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <img src="<?php echo BASE_URL; ?>assets/images/placeholder-image.png" id="newDonationImagePreview" class="img-fluid rounded border" alt="Preview da imagem">
                            </div>
                            <div class="col-md-8">
                                <input class="form-control" type="file" id="newDonationImageInput" name="image" accept="image/*" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Título do Item</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Ex: Sofá Retrátil 3 Lugares" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="category_id" class="form-label">Categoria</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="" selected disabled>Selecione</option>
                                <?php
                                $all_categories = $donation_category_controller->list_all();
                                foreach ($all_categories as $category) {
                                    echo '<option value="' . $category->id . '">' . htmlspecialchars($category->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Descreva o item, seu estado de conservação, medidas, etc." required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="editCondition" class="form-label">Condição</label>
                            <select class="form-select" id="editCondition" name="condition">
                                <option value="Usado">Usado</option>
                                <option value="Seminovo">Seminovo</option>
                                <option value="Novo">Novo</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status">
                                <option value="Disponível">Disponível</option>
                                <option value="Reservado">Reservado</option>
                                <option value="Doado">Doado</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="editNeighborhood" class="form-label">Bairro para Retirada</label>
                            <input type="text" class="form-control" id="editNeighborhood" name="neighborhood" placeholder="Ex: Centro, Vila Nova.." required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Publicar Doação</button>
                </div>
            </form>
        </div>
    </div>
</div>

</footer>