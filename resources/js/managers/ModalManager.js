export class ModalManager {
    constructor() {
        this.types = {
            customer: 'مشتری',
            car: 'خودرو',
            booking: 'رزرو',
            report: 'گزارش',
            option: 'آپشن',
            role: "نقش"
        };
    }

    initialize() {
        this.setupModalHandlers();
        this.setupDeleteHandler();
    }

    setupModalHandlers() {
        // Define global modal open/close functions
        window.openModal = (modalId) => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                // Reinitialize select handlers when modal opens
                if (window.SelectManager) {
                    window.SelectManager.initialize();
                }
            }
        };

        window.closeModal = (modalId) => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        };

        // Setup click handlers for modal backgrounds
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (!modal.querySelector('.bg-white').contains(e.target)) {
                    closeModal(modal.id);
                }
            });
        });

        // Setup click handlers for modal close buttons
        document.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const modal = button.closest('.modal');
                if (modal) {
                    closeModal(modal.id);
                }
            });
        });
    }

    setupDeleteHandler() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const deleteModal = document.getElementById('deleteModal');
        
        if (!deleteButtons.length || !deleteModal) return;

        deleteButtons.forEach(button => {
            button.onclick = (e) => {
                e.preventDefault();
                const { route, type } = button.dataset;
                if (route) {
                    this.configureDeleteModal(deleteModal, route, type);
                    openModal('deleteModal');
                }
            };
        });

        // Setup close functionality for delete modal
        deleteModal.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal('deleteModal');
            });
        });
    }

    configureDeleteModal(modal, route, type) {
        const form = modal.querySelector('#deleteForm');
        const itemType = this.types[type] || 'آیتم';
        
        form.action = route;
        modal.querySelector('h3').textContent = `تایید حذف ${itemType}`;
        modal.querySelector('p').textContent = `آیا از حذف این ${itemType} اطمینان دارید؟`;
    }
}