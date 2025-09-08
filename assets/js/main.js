document.addEventListener('DOMContentLoaded', function () {
    "use strict";

    // 1. INICIALIZAÇÃO DO AOS (ANIMAÇÕES)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }

    // 2. INICIALIZAÇÃO DO GLIGHTBOX (GALERIA DE IMAGENS)
    if (typeof GLightbox !== 'undefined') {
        const lightbox = GLightbox({
            selector: '.glightbox'
        });
    }

    // 3. SCRIPT PARA O MODAL DE ALTERAR SENHA (PÁGINA MINHA-CONTA)
    const passwordForm = document.getElementById('changePasswordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function (event) {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            if (password.value !== confirmPassword.value) {
                event.preventDefault();
                confirmPassword.classList.add('is-invalid');
            } else {
                confirmPassword.classList.remove('is-invalid');
            }
        });
    }

    // 4. SCRIPT PARA ATIVAR A ABA CORRETA NA PÁGINA MINHA-CONTA
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'donations') {
        const donationsTab = document.getElementById('v-pills-donations-tab');
        if (donationsTab) {
            new bootstrap.Tab(donationsTab).show();
        }
    }

    // 5. SCRIPT PARA O PREVIEW DA IMAGEM DO MODAL DE NOVA DOAÇÃO
    const imageInput = document.getElementById('newDonationImageInput');
    const imagePreview = document.getElementById('newDonationImagePreview');
    const newDonationModal = document.getElementById('newDonationModal');

    if (imagePreview) {
        const placeholderSrc = imagePreview.src;

        if (imageInput) {
            imageInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        if (newDonationModal) {
            newDonationModal.addEventListener('hidden.bs.modal', function () {
                document.getElementById('newDonationForm').reset();
                imagePreview.src = placeholderSrc;
            });
        }
    }

    // LÓGICA PARA A PÁGINA DE DOAÇÕES NO PERFIL DO USUÁRIO
    const editDonationModal = document.getElementById('editDonationModal');
    if (editDonationModal) {
        const imageInput = document.getElementById('editImageInput');
        const imagePreview = document.getElementById('editImagePreview');

        editDonationModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');
            const title = button.getAttribute('data-title');
            const description = button.getAttribute('data-description');
            const category_id = button.getAttribute('data-category_id');
            const condition = button.getAttribute('data-condition');
            const status = button.getAttribute('data-status');
            const neighborhood = button.getAttribute('data-neighborhood');
            const imageUrl = button.getAttribute('data-image_url');

            this.querySelector('.modal-title').textContent = 'Editar Doação: ' + title;
            this.querySelector('#editDonationId').value = id;
            this.querySelector('#editTitle').value = title;
            this.querySelector('#editDescription').value = description;
            this.querySelector('#editCategoryId').value = category_id;
            this.querySelector('#editCondition').value = condition;
            this.querySelector('#editStatus').value = status;
            this.querySelector('#editNeighborhood').value = neighborhood;

            imagePreview.src = imageUrl;
            imageInput.value = '';
        });

        if (imageInput) {
            imageInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    const confirmDeleteModal = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModal) {
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const title = button.getAttribute('data-title');
            this.querySelector('#donationNameToDelete').textContent = title;
            this.querySelector('#deleteDonationId').value = id;
        });
    }

});