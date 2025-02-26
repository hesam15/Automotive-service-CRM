export class UIManager {
    constructor() {
        this.elements = {};
        this.scrollTimer = null;
    }

    initialize() {
        this.initializeSidebar();
        this.initializeDropdowns();
        this.initializeBreadcrumbBehavior();
        this.initializeAlerts();
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

        window.addEventListener('scroll', () => {
            if (breadcrumbContainer) {
                breadcrumbContainer.style.transform = 'translateY(-100%)';
                clearTimeout(this.scrollTimer);
                this.scrollTimer = setTimeout(() => {
                    breadcrumbContainer.style.transform = 'translateY(0)';
                }, 500);
            }
        });
    }

    initializeAlerts() {
        document.querySelectorAll('.alert-dismissible').forEach(alert => {
            setTimeout(() => {
                alert.classList.add('opacity-0');
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        });
    }
}