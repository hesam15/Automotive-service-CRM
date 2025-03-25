class ModalManager {
    constructor() {
        this.types = {
            customer: 'مشتری',
            car: 'خودرو',
            booking: 'رزرو',
            report: 'گزارش',
            option: 'آپشن',
            role: "نقش",
            user: "کاربر",
            phone: "تلفن",
        };
        
        this.activeModal = null;
    }

    initialize() {
        this.setupModalTriggers();
        this.setupModalHandlers();
        this.setupDeleteHandler();
    }

    setupModalTriggers() {
        // Handle all modal triggers
        document.querySelectorAll('.modal-trigger').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.dataset.modalTarget;
                if (modalId) {
                    this.openModal(modalId);
                }
            });
        });
    }

    openModal(modalId) {
        try {
            const modal = document.getElementById(modalId);
            if (!modal) {
                throw new Error(`Modal with id ${modalId} not found`);
            }

            // Close any active modal first
            if (this.activeModal) {
                this.closeModal(this.activeModal.id);
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            this.activeModal = modal;

            // Dispatch custom event
            modal.dispatchEvent(new CustomEvent('modal:opened'));
        } catch (error) {
            console.error('Error opening modal:', error);
        }
    }

    closeModal(modalId) {
        try {
            const modal = document.getElementById(modalId);
            if (!modal) {
                throw new Error(`Modal with id ${modalId} not found`);
            }

            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            this.activeModal = null;

            // Reset form if exists
            const form = modal.querySelector('form');
            if (form) form.reset();

            // Dispatch custom event
            modal.dispatchEvent(new CustomEvent('modal:closed'));
        } catch (error) {
            console.error('Error closing modal:', error);
        }
    }

    setupModalHandlers() {
        // Handle clicking outside modal
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                const modalContent = modal.querySelector('.modal-content');
                if (modalContent && !modalContent.contains(e.target)) {
                    this.closeModal(modal.id);
                }
            });
        });

        // Handle close buttons
        document.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const modal = button.closest('.modal');
                if (modal) {
                    this.closeModal(modal.id);
                }
            });
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeModal) {
                this.closeModal(this.activeModal.id);
            }
        });
    }

    setupDeleteHandler() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const deleteModal = document.getElementById('deleteModal');
        
        if (!deleteButtons.length || !deleteModal) return;

        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const { route, type } = button.dataset;
                if (!route) {
                    console.error('Delete button missing route data attribute');
                    return;
                }
                
                this.configureDeleteModal(deleteModal, route, type);
                this.openModal('deleteModal');
            });
        });
    }

    configureDeleteModal(modal, route, type) {
        try {
            const form = modal.querySelector('#deleteForm');
            if (!form) {
                throw new Error('Delete form not found in modal');
            }

            const itemType = this.types[type] || 'آیتم';
            form.action = route;

            const title = modal.querySelector('h3');
            const message = modal.querySelector('p');
            
            if (title) title.textContent = `تایید حذف ${itemType}`;
            if (message) message.textContent = `آیا از حذف این ${itemType} اطمینان دارید؟`;

        } catch (error) {
            console.error('Error configuring delete modal:', error);
        }
    }
}

export default ModalManager;