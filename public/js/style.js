document.addEventListener('DOMContentLoaded', function() {
    App.initialize();
});

const App = {
    initialize() {
        UIManager.initialize();
        ModalManager.initialize();
        OptionsManager.initialize();
        SelectManager.initialize();
        ExplanationManager.initialize();
        AccordionManager.initialize();
    }
};

const UIManager = {
    initialize() {
        this.initializeSidebar();
        this.initializeDropdowns();
        this.initializeBreadcrumbBehavior();
        this.initializeAlerts();
    },

    initializeSidebar() {
        const elements = {
            toggle: document.getElementById('sidebarToggle'),
            sidebar: document.getElementById('sidebar'),
            overlay: document.getElementById('sidebarOverlay')
        };

        if (Object.values(elements).every(Boolean)) {
            const toggleSidebar = () => {
                elements.sidebar.classList.toggle('translate-x-full');
                elements.overlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            };

            elements.toggle.addEventListener('click', toggleSidebar);
            elements.overlay.addEventListener('click', toggleSidebar);
        }
    },

    initializeDropdowns() {
        ['services', 'roles', 'users', 'customers'].forEach(menu => {
            const elements = {
                button: document.getElementById(`${menu}Button`),
                menu: document.getElementById(`${menu}Menu`),
                icon: document.getElementById(`${menu}Icon`)
            };

            if (Object.values(elements).every(Boolean)) {
                elements.button.addEventListener('click', () => {
                    const isExpanded = elements.menu.style.maxHeight !== '0px';
                    elements.menu.style.maxHeight = isExpanded ? '0px' : '160px';
                    elements.icon.style.transform = `rotate(${isExpanded ? 0 : 180}deg)`;
                });
            }
        });

        this.initializeUserDropdown();
    },

    initializeUserDropdown() {
        const elements = {
            button: document.getElementById('userDropdown'),
            menu: document.getElementById('userMenu'),
            icon: document.getElementById('userDropdown')?.querySelector('.material-icons-round:last-child')
        };

        if (elements.button && elements.menu) {
            elements.button.addEventListener('click', (e) => {
                e.stopPropagation();
                const isExpanded = !elements.menu.classList.contains('hidden');
                elements.menu.classList.toggle('hidden');
                if (elements.icon) {
                    elements.icon.style.transform = `rotate(${isExpanded ? 0 : 180}deg)`;
                }
            });

            document.addEventListener('click', (e) => {
                if (!elements.button.contains(e.target)) {
                    elements.menu.classList.add('hidden');
                    if (elements.icon) {
                        elements.icon.style.transform = 'rotate(0deg)';
                    }
                }
            });
        }
    },

    initializeBreadcrumbBehavior() {
        const breadcrumbContainer = document.querySelector('.breadcrumb-container');
        let scrollTimer;

        window.addEventListener('scroll', () => {
            if (breadcrumbContainer) {
                breadcrumbContainer.style.transform = 'translateY(-100%)';
                clearTimeout(scrollTimer);
                scrollTimer = setTimeout(() => {
                    breadcrumbContainer.style.transform = 'translateY(0)';
                }, 500);
            }
        });
    },

    initializeAlerts() {
        document.querySelectorAll('.alert-dismissible').forEach(alert => {
            setTimeout(() => {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        });
    }
};

const ModalManager = {
    initialize() {
        this.setupModalHandlers();
        this.setupDeleteHandler();
    },

    setupModalHandlers() {
        window.openModal = (modalId) => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                SelectManager.initialize();
            }
        };

        window.closeModal = (modalId) => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        };

        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (!modal.querySelector('.bg-white').contains(e.target)) {
                    closeModal(modal.id);
                }
            });
        });

        // Add event listeners for modal close buttons
        document.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const modal = button.closest('.modal');
                if (modal) {
                    closeModal(modal.id);
                }
            });
        });
    },

    setupDeleteHandler() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const deleteModal = document.getElementById('deleteModal');
        
        if (!deleteButtons.length || !deleteModal) return;

        const types = {
            customer: 'مشتری',
            car: 'خودرو',
            booking: 'رزرو',
            report: 'گزارش',
            option: 'آپشن',
            role: "نقش"
        };

        deleteButtons.forEach(button => {
            button.onclick = function(e) {
                e.preventDefault();
                const { route, type } = this.dataset;
                if (route) {
                    const form = deleteModal.querySelector('#deleteForm');
                    const itemType = types[type] || 'آیتم';
                    
                    form.action = route;
                    deleteModal.querySelector('h3').textContent = `تایید حذف ${itemType}`;
                    deleteModal.querySelector('p').textContent = `آیا از حذف این ${itemType} اطمینان دارید؟`;
                    
                    openModal('deleteModal');
                }
            };
        });

        // Ensure close functionality for delete modal
        deleteModal.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal('deleteModal');
            });
        });
    }
};

const OptionsManager = {
    initialize() {
        this.initializeOptionsForm();
    },

    initializeOptionsForm() {
        const elements = {
            addButton: document.getElementById('option_add'),
            removeButton: document.getElementById('option_remove'),
            container: document.getElementById('options_container')
        };

        if (Object.values(elements).every(Boolean)) {
            this.setupOptionButtons(elements);
        }
    },

    setupOptionButtons(elements) {
        elements.addButton.addEventListener('click', () => {
            const newIndex = elements.container.querySelectorAll('.option-field').length;
            elements.container.appendChild(this.createOptionField(newIndex));
        });

        elements.removeButton.addEventListener('click', () => {
            const fields = elements.container.getElementsByClassName('option-field');
            if (fields.length > 1) {
                fields[fields.length - 1].remove();
            }
        });
    },

    createOptionField(index) {
        const div = document.createElement('div');
        div.className = 'option-field grid grid-cols-2 gap-6';
        div.innerHTML = this.getOptionFieldTemplate(index);
        return div;
    },

    getOptionFieldTemplate(index) {
        return `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">خدمات</label>
                <input type="text" name="sub_options[${index}]" placeholder="نام آپشن"
                       class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">مقادیر</label>
                <input type="text" name="sub_values[${index}]" placeholder="مقادیر را با ، جدا کنید"
                       class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
            </div>`;
    }
};

const SelectManager = {
    initialize() {
        document.querySelectorAll('[id^="editModal-"]').forEach(modal => {
            const selectAllBtn = modal.querySelector('#selectAll');
            const checkboxes = modal.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
            const buttonText = modal.querySelector('#selectAllText');

            if (selectAllBtn && checkboxes.length > 0 && buttonText) {
                this.setupSelectAllHandler(selectAllBtn, checkboxes, buttonText);
            }
        });
    },

    setupSelectAllHandler(button, checkboxes, buttonText) {
        const checkState = () => Array.from(checkboxes).every(cb => cb.checked);
        const updateButtonText = () => {
            buttonText.textContent = checkState() ? 'برداشتن همه' : 'انتخاب همه';
        };

        button.onclick = () => {
            const newState = !checkState();
            checkboxes.forEach(cb => cb.checked = newState);
            updateButtonText();
        };

        updateButtonText();
        checkboxes.forEach(cb => cb.addEventListener('change', updateButtonText));
    }
};

const ExplanationManager = {
    initialize() {
        this.setupExplanationToggles();
    },

    setupExplanationToggles() {
        window.toggleExplanation = (serviceName) => {
            const container = document.getElementById(`explanation_${serviceName}`);
            const button = document.querySelector(`[data-service="${serviceName}"]`);
            
            if (container) {
                // اگر textarea وجود دارد، آن را حذف می‌کنیم
                if (container.querySelector('textarea')) {
                    container.innerHTML = '';
                    if (button) {
                        const icon = button.querySelector('svg');
                        if (icon) {
                            icon.style.transform = 'rotate(0deg)';
                        }
                    }
                } 
                // اگر textarea وجود ندارد، آن را ایجاد می‌کنیم
                else {
                    const textarea = document.createElement('textarea');
                    textarea.name = `${serviceName}_explanation`;
                    textarea.rows = 3;
                    textarea.className = 'w-full px-4 py-2.5 text-sm text-gray-900 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200 mb-3';
                    textarea.placeholder = 'توضیحات خود را اینجا وارد کنید...';
                    
                    container.appendChild(textarea);
                    
                    if (button) {
                        const icon = button.querySelector('svg');
                        if (icon) {
                            icon.style.transform = 'rotate(45deg)';
                        }
                    }

                    // اسکرول به موقعیت textarea
                    textarea.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        };

        document.querySelectorAll('.explanation-toggle').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const serviceName = button.dataset.service;
                if (serviceName) {
                    toggleExplanation(serviceName);
                }
            });
        });
    }
};

const AccordionManager = {
    initialize() {
        this.setupAccordion();
        this.setupServiceAccordions();
    },

    setupAccordion() {
        const accordionButton = document.getElementById('infoAccordion');
        if (accordionButton) {
            accordionButton.addEventListener('click', () => {
                const content = document.getElementById('infoContent');
                const icon = document.getElementById('accordionIcon');
                this.toggleAccordion(content, icon);
            });

            // Set initial state to closed for main accordion
            const content = document.getElementById('infoContent');
            const icon = document.getElementById('accordionIcon');
            if (content && icon) {
                this.setInitialState(content, icon);
            }
        }
    },

    setupServiceAccordions() {
        window.toggleService = (serviceKey) => {
            const content = document.getElementById(`content_${serviceKey}`);
            const icon = document.getElementById(`icon_${serviceKey}`);
            this.toggleAccordion(content, icon);
        };

        // Set initial state for all service accordions
        document.querySelectorAll('[id^="content_"]').forEach((content) => {
            const serviceKey = content.id.replace('content_', '');
            const icon = document.getElementById(`icon_${serviceKey}`);
            this.setInitialState(content, icon);
        });
    },

    toggleAccordion(content, icon) {
        if (content && icon) {
            const isHidden = content.classList.contains('hidden');
            
            // Toggle content visibility with smooth animation
            if (isHidden) {
                content.classList.remove('hidden');
                setTimeout(() => {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }, 0);
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.maxHeight = '0px';
                icon.style.transform = 'rotate(0deg)';
                setTimeout(() => {
                    content.classList.add('hidden');
                }, 200); // Match with CSS transition duration
            }
        }
    },

    setInitialState(content, icon) {
        if (content) {
            content.classList.add('hidden');
            content.style.maxHeight = '0px';
        }
        if (icon) {
            icon.style.transform = 'rotate(0deg)';
        }
    }
};