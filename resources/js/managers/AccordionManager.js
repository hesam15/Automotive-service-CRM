// resources/js/managers/AccordionManager.js

/**
 * Manages accordion functionality throughout the application
 */
export class AccordionManager {
    /**
     * Initialize the AccordionManager
     */
    constructor() {
        this.initialize();
    }

    /**
     * Initialize event listeners and setup
     */
    initialize() {
        this.setupGlobalHandlers();
    }

    /**
     * Setup global handlers and make them available on window object
     */
    setupGlobalHandlers() {
        // Make toggleService available globally
        window.toggleService = (serviceKey) => {
            try {
                const content = document.getElementById(`content_${serviceKey}`);
                const icon = document.getElementById(`icon_${serviceKey}`);
                
                if (!content || !icon) {
                    console.error(`Elements not found for service key: ${serviceKey}`);
                    return;
                }

                this.toggleAccordion(content, icon);
            } catch (error) {
                console.error('Error in toggleService:', error);
            }
        };
    }

    /**
     * Toggle accordion content visibility
     * @param {HTMLElement} content - The content element to toggle
     * @param {HTMLElement} icon - The icon element to rotate
     */
    toggleAccordion(content, icon) {
        try {
            // Toggle content visibility
            if (content.classList.contains('hidden')) {
                // Show content
                content.classList.remove('hidden');
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.classList.add('rotate-180');
            } else {
                // Hide content
                content.classList.add('hidden');
                content.style.maxHeight = '0';
                icon.classList.remove('rotate-180');
            }

            // Add smooth transition
            content.style.transition = 'max-height 0.3s ease-in-out';
        } catch (error) {
            console.error('Error in toggleAccordion:', error);
        }
    }

    /**
     * Close all accordions except the one specified
     * @param {string} exceptId - ID of the accordion to keep open
     */
    closeOtherAccordions(exceptId) {
        try {
            const allContents = document.querySelectorAll('[id^="content_"]');
            const allIcons = document.querySelectorAll('[id^="icon_"]');

            allContents.forEach((content) => {
                if (content.id !== exceptId && !content.classList.contains('hidden')) {
                    const icon = document.getElementById(content.id.replace('content_', 'icon_'));
                    this.toggleAccordion(content, icon);
                }
            });
        } catch (error) {
            console.error('Error in closeOtherAccordions:', error);
        }
    }
}

// Create and export a singleton instance
export const accordionManager = new AccordionManager();