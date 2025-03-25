class AccordionManager {
    static instance = null;

    constructor() {
        // Singleton Pattern
        if (AccordionManager.instance) {
            console.log('AccordionManager: Returning existing instance');
            return AccordionManager.instance;
        }

        console.log('AccordionManager: Constructor initialized');
        this.accordions = new Map();
        this.initialized = false;
        AccordionManager.instance = this;
    }

    static getInstance() {
        if (!AccordionManager.instance) {
            AccordionManager.instance = new AccordionManager();
        }
        return AccordionManager.instance;
    }

    initialize() {
        // Prevent multiple initializations
        if (this.initialized) {
            console.log('AccordionManager: Already initialized');
            return;
        }

        console.log('AccordionManager: Initialize called');
        try {
            this.setupAccordions();
            this.setupGlobalHandlers();
            this.initialized = true;
            console.log('AccordionManager: Initialization complete');
        } catch (error) {
            console.error('AccordionManager Error:', error);
        }
    }

    setupAccordions() {
        // Find all accordion buttons
        const accordionButtons = document.querySelectorAll('[id$="Accordion"]');
        
        accordionButtons.forEach(button => {
            const id = button.id;
            const baseId = id.replace('Accordion', '');
            const content = document.getElementById(`${baseId}Content`);
            const icon = document.getElementById(`${baseId}Icon`) || button.querySelector('svg');
            
            if (content && icon) {
                // Store accordion elements
                this.accordions.set(id, {
                    button,
                    content,
                    icon,
                    isOpen: false
                });
                
                // Remove existing event listeners to prevent duplicates
                button.removeEventListener('click', this.toggleAccordion);
                
                // Add new event listener
                button.addEventListener('click', () => this.toggleAccordion(id));
            }
        });

        console.log(`AccordionManager: Found ${this.accordions.size} accordions`);
    }

    setupGlobalHandlers() {
        // Maintain backward compatibility with the old toggleService function
        window.toggleService = (serviceKey) => {
            try {
                const content = document.getElementById(`content_${serviceKey}`);
                const icon = document.getElementById(`icon_${serviceKey}`);
                
                if (!content || !icon) {
                    console.error(`Elements not found for service key: ${serviceKey}`);
                    return;
                }

                this.toggleAccordionElements(content, icon);
            } catch (error) {
                console.error('Error in toggleService:', error);
            }
        };
    }

    toggleAccordion(id) {
        const accordion = this.accordions.get(id);
        if (!accordion) return;

        const { isOpen } = accordion;
        
        if (isOpen) {
            this.closeAccordion(id);
        } else {
            this.openAccordion(id);
        }
    }

    openAccordion(id) {
        const accordion = this.accordions.get(id);
        if (!accordion) return;

        const { content, icon } = accordion;

        // Set initial height to 0
        content.style.maxHeight = '0';
        
        // Remove hidden class and add transition
        content.classList.remove('hidden');
        content.style.transition = 'max-height 0.3s ease-in-out';
        
        // Force a reflow to ensure the transition works
        content.offsetHeight;
        
        // Set the final height
        requestAnimationFrame(() => {
            content.style.maxHeight = `${content.scrollHeight}px`;
            icon.classList.add('rotate-180');
        });

        // Update state
        accordion.isOpen = true;
        this.accordions.set(id, accordion);
    }

    closeAccordion(id) {
        const accordion = this.accordions.get(id);
        if (!accordion) return;

        const { content, icon } = accordion;

        // Get the current height
        content.style.maxHeight = `${content.scrollHeight}px`;
        
        // Force a reflow
        content.offsetHeight;
        
        // Set transition and animate to 0
        content.style.transition = 'max-height 0.3s ease-in-out';
        content.style.maxHeight = '0';
        
        // Rotate icon back
        icon.classList.remove('rotate-180');
        
        // Hide content after animation
        setTimeout(() => {
            content.classList.add('hidden');
            content.style.transition = '';
        }, 300);

        // Update state
        accordion.isOpen = false;
        this.accordions.set(id, accordion);
    }

    toggleAccordionElements(content, icon) {
        try {
            if (content.classList.contains('hidden')) {
                // Opening
                content.style.maxHeight = '0';
                content.classList.remove('hidden');
                content.style.transition = 'max-height 0.3s ease-in-out';
                
                // Force reflow
                content.offsetHeight;
                
                // Animate to full height
                requestAnimationFrame(() => {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.classList.add('rotate-180');
                });
            } else {
                // Closing
                content.style.maxHeight = content.scrollHeight + 'px';
                content.offsetHeight;
                content.style.transition = 'max-height 0.3s ease-in-out';
                content.style.maxHeight = '0';
                icon.classList.remove('rotate-180');
                
                setTimeout(() => {
                    content.classList.add('hidden');
                    content.style.transition = '';
                }, 300);
            }
        } catch (error) {
            console.error('Error in toggleAccordionElements:', error);
        }
    }

    isInitialized() {
        return this.initialized;
    }
}

export default AccordionManager;
