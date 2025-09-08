document.addEventListener('DOMContentLoaded', function () {

    // 1. LÓGICA PARA PÁGINA DE DOAÇÕES DO ADMIN

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

    // 2. LÓGICA PARA PÁGINA DE CATEGORIAS DO ADMIN

    const editCategoryModal = document.getElementById('editCategoryModal');
    if (editCategoryModal) {
        editCategoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const modalTitle = this.querySelector('.modal-title');
            const actionTypeInput = this.querySelector('#categoryActionType');
            const categoryIdInput = this.querySelector('#editCategoryId');
            const categoryNameInput = this.querySelector('#editCategoryName');
            const saveBtn = this.querySelector('#saveCategoryBtn');

            const id = button.getAttribute('data-id');
            if (id) {

                const name = button.getAttribute('data-name');
                modalTitle.textContent = 'Editar Categoria: ' + name;
                actionTypeInput.value = 'edit';
                categoryIdInput.value = id;
                categoryNameInput.value = name;
                saveBtn.textContent = 'Salvar Alterações';
            } else {

                modalTitle.textContent = 'Nova Categoria';
                actionTypeInput.value = 'create';
                categoryIdInput.value = '';
                categoryNameInput.value = '';
                saveBtn.textContent = 'Salvar';
            }
        });
    }

    const confirmDeleteCategoryModal = document.getElementById('confirmDeleteCategoryModal');
    if (confirmDeleteCategoryModal) {
        confirmDeleteCategoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            this.querySelector('#categoryNameToDelete').textContent = name;
            this.querySelector('#deleteCategoryId').value = id;
        });
    }

    // 3. LÓGICA PARA PÁGINA DE PONTOS DE COLETA 
    const editPointModal = document.getElementById('editPointModal');
    if (editPointModal) {
        editPointModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const modalTitle = this.querySelector('.modal-title');
            const actionTypeInput = this.querySelector('#pointActionType');
            const pointIdInput = this.querySelector('#editPointId');
            const saveBtn = this.querySelector('#savePointBtn');
            const form = this.querySelector('#editPointForm');

            form.reset();

            const id = button.getAttribute('data-id');
            if (id) {

                modalTitle.textContent = 'Editar Ponto de Coleta';
                actionTypeInput.value = 'edit';
                pointIdInput.value = id;
                saveBtn.textContent = 'Salvar Alterações';

                this.querySelector('#editPointName').value = button.getAttribute('data-name');
                this.querySelector('#editPointStreet').value = button.getAttribute('data-street');
                this.querySelector('#editPointNumber').value = button.getAttribute('data-number');
                this.querySelector('#editPointNeighborhood').value = button.getAttribute('data-neighborhood');
                this.querySelector('#editPointCity').value = button.getAttribute('data-city');
                this.querySelector('#editPointState').value = button.getAttribute('data-state');
                this.querySelector('#editPointMaterials').value = button.getAttribute('data-materials');
                this.querySelector('#editPointCategory').value = button.getAttribute('data-category');
            } else {
                modalTitle.textContent = 'Novo Ponto de Coleta';
                actionTypeInput.value = 'create';
                pointIdInput.value = '';
                saveBtn.textContent = 'Salvar';
            }
        });
    }

    const confirmDeletePointModal = document.getElementById('confirmDeletePointModal');
    if (confirmDeletePointModal) {
        confirmDeletePointModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            this.querySelector('#pointNameToDelete').textContent = name;
            this.querySelector('#deletePointId').value = id;
        });
    }

    // 4. LÓGICA PARA PÁGINA DE USUÁRIOS ADMIN 
    const editUserModal = document.getElementById('editUserModal');
    if (editUserModal) {
        editUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const form = this.querySelector('#editUserForm');
            const modalTitle = this.querySelector('.modal-title');
            const actionTypeInput = this.querySelector('#userActionType');
            const userIdInput = this.querySelector('#editUserId');
            const passwordInput = this.querySelector('#editUserPassword');
            const saveBtn = this.querySelector('#saveUserBtn');

            form.reset();

            const id = button.getAttribute('data-id');
            if (id) {
                modalTitle.textContent = 'Editar Usuário Admin';
                actionTypeInput.value = 'edit';
                userIdInput.value = id;
                saveBtn.textContent = 'Salvar Alterações';
                passwordInput.placeholder = 'Deixe em branco para não alterar';
                passwordInput.required = false;

                this.querySelector('#editUserName').value = button.getAttribute('data-name');
                this.querySelector('#editUserUsername').value = button.getAttribute('data-username');
                this.querySelector('#editUserAuthLevel').value = button.getAttribute('data-auth_level');
            } else {
                modalTitle.textContent = 'Novo Usuário Admin';
                actionTypeInput.value = 'create';
                userIdInput.value = '';
                saveBtn.textContent = 'Salvar';
                passwordInput.placeholder = 'Crie uma senha forte';
                passwordInput.required = true;
            }
        });
    }

    const confirmDeleteUserModal = document.getElementById('confirmDeleteUserModal');
    if (confirmDeleteUserModal) {
        confirmDeleteUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            this.querySelector('#userNameToDelete').textContent = name;
            this.querySelector('#deleteUserId').value = id;
        });
    }

    // 5. LÓGICA PARA PÁGINA DE USUÁRIOS DO SITE
    const editSiteUserModal = document.getElementById('editSiteUserModal');
    if (editSiteUserModal) {
        editSiteUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const form = this.querySelector('#editSiteUserForm');

            form.reset();

            const id = button.getAttribute('data-id');
            if (id) {
                this.querySelector('#editSiteUserId').value = id;
                this.querySelector('#editSiteUserFullName').value = button.getAttribute('data-full_name');
                this.querySelector('#editSiteUserEmail').value = button.getAttribute('data-email');
                this.querySelector('#editSiteUserPhone').value = button.getAttribute('data-phone_number');
            }
        });
    }

    // 6. LÓGICA PARA O MODAL DE DETALHES DO LOG
    const confirmDeleteSiteUserModal = document.getElementById('confirmDeleteSiteUserModal');
    if (confirmDeleteSiteUserModal) {
        confirmDeleteSiteUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            this.querySelector('#siteUserNameToDelete').textContent = name;
            this.querySelector('#deleteSiteUserId').value = id;
        });
    }

    const logDetailsModal = document.getElementById('logDetailsModal');
    if (logDetailsModal) {
        logDetailsModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const details = button.getAttribute('data-details');
            const codeElement = this.querySelector('code');

            if (details) {
                try {
                    const parsedDetails = JSON.parse(details);
                    codeElement.textContent = JSON.stringify(parsedDetails, null, 2);
                } catch (e) {
                    codeElement.textContent = 'Erro ao ler os detalhes. Conteúdo bruto:\n' + details;
                }
            } else {
                codeElement.textContent = 'Nenhum detalhe adicional fornecido.';
            }
        });
    }

    // 7. LÓGICA PARA O GRÁFICO DE VENDAS
    const data = {
        labels: weeklyActivityLabels,
        datasets: [{
            label: 'Novas Doações',
            data: weeklyActivityData,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            cubicInterpolationMode: 'monotone',
            pointRadius: 5,
            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
            pointBorderColor: '#fff',
            pointHoverRadius: 7,
            pointHoverBackgroundColor: 'rgba(54, 162, 235, 1)',
            pointHoverBorderColor: 'rgba(220,220,220,1)'
        }]
    };

    const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    boxWidth: 20,
                    padding: 10
                }
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function (context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += context.parsed.y + ' doações';
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 11
                    }
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                },
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 11
                    }
                }
            }
        }
    };

    const ctx = document.getElementById('activityChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: data,
        options: options
    });
});

