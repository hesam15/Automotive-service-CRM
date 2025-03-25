class UIManager {
    static instance = null;

    constructor() {
        if (UIManager.instance) {
            return UIManager.instance;
        }

        this.elements = {};
        this.scrollTimer = null;
        UIManager.instance = this;
    }

    initialize() {
        try {
            this.setupToastContainer();
            this.initializeSidebar();
            this.initializeDropdowns();
            this.initializeBreadcrumbBehavior();
            this.initializeAlerts();
        } catch (error) {
            console.error('UIManager: Initialization failed:', error);
        }
    }

    setupToastContainer() {
        if (!document.getElementById('toast-container')) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed bottom-4 right-4 z-50';
            document.body.appendChild(container);
        }
    }

    static showToast(message, type = 'success') {
        if (!UIManager.instance) {
            UIManager.instance = new UIManager();
        }
        UIManager.instance.setupToastContainer();

        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        const baseClasses = 'p-4 mb-2 rounded-lg text-white';
        const typeClasses = type === 'error' ? 'bg-red-500' : 'bg-green-500';
        
        toast.className = `${baseClasses} ${typeClasses}`;
        toast.textContent = message;
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        toast.style.transition = 'all 0.3s ease';
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    static showSuccess(message) {
        this.showToast(message, 'success');
    }

    static showError(message) {
        this.showToast(message, 'error');
    }

    initializeSidebar() {
        this.elements = {
            toggle: document.getElementById('sidebarToggle'),
            sidebar: document.getElementById('sidebar'),
            overlay: document.getElementById('sidebarOverlay')
        };

        if (Object.values(this.elements).every(Boolean)) {
            this.setupSidebarEvents();
        }
    }

    setupSidebarEvents() {
        const toggleSidebar = () => {
            this.elements.sidebar.classList.toggle('translate-x-full');
            this.elements.overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        };

        this.elements.toggle.addEventListener('click', toggleSidebar);
        this.elements.overlay.addEventListener('click', toggleSidebar);
    }

    initializeDropdowns() {
        ['services', 'roles', 'users', 'customers'].forEach(menu => {
            const elements = {
                button: document.getElementById(`${menu}Button`),
                menu: document.getElementById(`${menu}Menu`),
                icon: document.getElementById(`${menu}Icon`)
            };

            if (Object.values(elements).every(Boolean)) {
                this.setupDropdownEvents(elements);
            }
        });

        this.initializeUserDropdown();
    }

    setupDropdownEvents(elements) {
        elements.button.addEventListener('click', () => {
            const isExpanded = elements.menu.style.maxHeight !== '0px';
            elements.menu.style.maxHeight = isExpanded ? '0px' : '160px';
            elements.icon.style.transform = `rotate(${isExpanded ? 0 : 180}deg)`;
        });
    }

    initializeUserDropdown() {
        const elements = {
            button: document.getElementById('userDropdown'),
            menu: document.getElementById('userMenu'),
            icon: document.getElementById('userDropdown')?.querySelector('.material-icons-round:last-child')
        };

        if (elements.button && elements.menu) {
            this.setupUserDropdownEvents(elements);
        }
    }

    setupUserDropdownEvents(elements) {
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

    initializeBreadcrumbBehavior() {
        const breadcrumbContainer = document.querySelector('.breadcrumb-container');
        if (!breadcrumbContainer) return;

        window.addEventListener('scroll', () => {
            breadcrumbContainer.style.transform = 'translateY(-100%)';
            clearTimeout(this.scrollTimer);
            this.scrollTimer = setTimeout(() => {
                breadcrumbContainer.style.transform = 'translateY(0)';
            }, 500);
        });
    }

    initializeAlerts() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        });
    }

    static getInstance() {
        if (!UIManager.instance) {
            UIManager.instance = new UIManager();
        }
        return UIManager.instance;
    }
}

// Bind static methods
UIManager.showToast = UIManager.showToast.bind(UIManager);
UIManager.showSuccess = UIManager.showSuccess.bind(UIManager);
UIManager.showError = UIManager.showError.bind(UIManager);

export default UIManager;