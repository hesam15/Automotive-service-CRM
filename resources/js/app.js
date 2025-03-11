// resources/js/app.js

import $ from 'jquery';
import Alpine from 'alpinejs';
import { UIManager } from './managers/UIManager';
import { ModalManager } from './managers/ModalManager';
import { OptionsManager } from './managers/OptionsManager';
import { accordionManager } from './managers/AccordionManager';

window.Alpine = Alpine;
Alpine.start();

// Initialize Managers
try {
    // Initialize Core Managers
    const uiManager = new UIManager();
    const modalManager = new ModalManager();
    const optionsManager = new OptionsManager();
    
    // Make toggleService globally available
    window.toggleService = (serviceKey) => {
        const content = document.getElementById(`content_${serviceKey}`);
        const icon = document.getElementById(`icon_${serviceKey}`);
        if (content && icon) {
            accordionManager.toggleAccordion(content, icon);
        }
    };

} catch (error) {
    console.error('Error initializing managers:', error);
}

// Error handling
window.addEventListener('error', (event) => {
    console.error('Global error handler:', event.error);
});

// Export managers for use in other files if needed
export {
    UIManager,
    ModalManager,
    OptionsManager,
    accordionManager
};