// resources/js/app.js
import $ from 'jquery';
import Alpine from 'alpinejs';
import { UIManager } from './managers/UIManager';
import { ModalManager } from './managers/ModalManager';
import { OptionsManager } from './managers/OptionsManager';

// Core initialization
window.Alpine = Alpine;

// Initialize managers
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Initialize UI Manager
        const uiManager = new UIManager();
        uiManager.initialize();

        // Initialize Modal Manager
        if (document.querySelector('.modal') || 
            document.querySelector('.modal-trigger') || 
            document.querySelector('.delete-btn')) {
            const modalManager = new ModalManager();
            modalManager.initialize();
        }

        // Initialize Options Manager for both create and edit pages
        if (document.querySelector('.option-values-container')) {
            const optionsManager = new OptionsManager({
                containers: document.querySelectorAll('.option-values-container'),
                addButtons: document.querySelectorAll('.option-add-btn'),
                removeButtons: document.querySelectorAll('.option-remove-btn')
            });
            optionsManager.initialize();
        }

        // Load other conditional managers if needed
        loadConditionalManagers();

    } catch (error) {
        console.error('Error initializing managers:', error);
    }
});

// Load other managers based on page needs
async function loadConditionalManagers() {
    try {
        // Load SelectManager if on roles page
        if (window.location.pathname.includes('/roles') || 
            document.querySelector('input[name="permissions[]"]')) {
            const { SelectManager } = await import('./managers/SelectManager');
            const selectManager = new SelectManager();
            selectManager.initialize();
        }

        // Load ExplanationManager and AccordionManager if needed
        if (document.querySelector('.explanation-toggle') || 
            document.getElementById('infoAccordion')) {
            const { ExplanationManager } = await import('./managers/ExplanationManager');
            const { AccordionManager } = await import('./managers/AccordionManager');
            
            const explanationManager = new ExplanationManager();
            const accordionManager = new AccordionManager();
            
            explanationManager.initialize();
            accordionManager.initialize();
        }
    } catch (error) {
        console.error('Error in conditional managers:', error);
    }
}

// Initialize Alpine.js
Alpine.start();