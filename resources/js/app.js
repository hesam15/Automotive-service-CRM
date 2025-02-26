import './bootstrap';
import $ from 'jquery';
import Alpine from 'alpinejs';
import { UIManager } from './managers/UIManager';

// Core initialization
window.Alpine = Alpine;
Alpine.start();

class App {
    constructor() {
        this.managers = {
            ui: new UIManager()
        };
        this.initializeConditionalManagers();
    }

    async initializeConditionalManagers() {
        try {
            await this.loadRolesManager();
            await this.loadOptionsManager();
            await this.loadModalManager();
            await this.loadServiceManagers();
        } catch (error) {
            console.error('Error initializing managers:', error);
        }
    }

    async loadRolesManager() {
        if (this.isRolesPage()) {
            const { SelectManager } = await import('./managers/SelectManager');
            this.managers.select = new SelectManager();
            await this.initializeManager(this.managers.select);
        }
    }

    async loadOptionsManager() {
        if (document.getElementById('options_container')) {
            const { OptionsManager } = await import('./managers/OptionsManager');
            this.managers.options = new OptionsManager();
            await this.initializeManager(this.managers.options);
        }
    }

    async loadModalManager() {
        if (document.querySelector('.modal') || document.querySelector('.delete-btn')) {
            const { ModalManager } = await import('./managers/ModalManager');
            this.managers.modal = new ModalManager();
            await this.initializeManager(this.managers.modal);
        }
    }

    async loadServiceManagers() {
        if (document.querySelector('.explanation-toggle') || document.getElementById('infoAccordion')) {
            const { ExplanationManager } = await import('./managers/ExplanationManager');
            const { AccordionManager } = await import('./managers/AccordionManager');
            
            this.managers.explanation = new ExplanationManager();
            this.managers.accordion = new AccordionManager();
            
            await Promise.all([
                this.initializeManager(this.managers.explanation),
                this.initializeManager(this.managers.accordion)
            ]);
        }
    }

    isRolesPage() {
        return window.location.pathname.includes('/roles') || 
               document.querySelector('input[name="permissions[]"]');
    }

    async initializeManager(manager) {
        if (manager && typeof manager.initialize === 'function') {
            try {
                await manager.initialize();
            } catch (error) {
                console.error(`Error initializing ${manager.constructor.name}:`, error);
            }
        }
    }

    initialize() {
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                await this.initializeManager(this.managers.ui);
            } catch (error) {
                console.error('Error in initialization:', error);
            }
        });
    }
}

const app = new App();
app.initialize();

export default App;