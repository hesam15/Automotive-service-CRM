export class AccordionManager {
    constructor() {
        this.mainAccordion = {
            button: document.getElementById('infoAccordion'),
            content: document.getElementById('infoContent'),
            icon: document.getElementById('accordionIcon')
        };
    }

    initialize() {
        this.setupAccordion();
        this.setupServiceAccordions();
    }

    setupAccordion() {
        if (this.mainAccordion.button) {
            this.mainAccordion.button.addEventListener('click', () => {
                this.toggleAccordion(
                    this.mainAccordion.content, 
                    this.mainAccordion.icon
                );
            });

            // Set initial state to closed for main accordion
            if (this.mainAccordion.content && this.mainAccordion.icon) {
                this.setInitialState(
                    this.mainAccordion.content, 
                    this.mainAccordion.icon
                );
            }
        }
    }

    setupServiceAccordions() {
        // Define global toggle function for services
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
    }

    toggleAccordion(content, icon) {
        if (!content || !icon) return;

        const isHidden = content.classList.contains('hidden');
        
        // Toggle content visibility with smooth animation
        if (isHidden) {
            this.showAccordion(content, icon);
        } else {
            this.hideAccordion(content, icon);
        }
    }

    showAccordion(content, icon) {
        content.classList.remove('hidden');
        setTimeout(() => {
            content.style.maxHeight = content.scrollHeight + 'px';
        }, 0);
        icon.style.transform = 'rotate(180deg)';
    }

    hideAccordion(content, icon) {
        content.style.maxHeight = '0px';
        icon.style.transform = 'rotate(0deg)';
        setTimeout(() => {
            content.classList.add('hidden');
        }, 200); // Match with CSS transition duration
    }

    setInitialState(content, icon) {
        if (content) {
            content.classList.add('hidden');
            content.style.maxHeight = '0px';
        }
        if (icon) {
            icon.style.transform = 'rotate(0deg)';
        }
    }
}