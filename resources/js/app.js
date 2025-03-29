import DatePickerManager from './managers/DatePickerManager';
import ModalManager from './managers/ModalManager';
import OptionsManager from './managers/OptionsManager';
import AccordionManager from './managers/AccordionManager';
import FormManager from './managers/FormManager';
import UIManager from './managers/UIManager';
import ExplanationManager from './managers/ExplanationManager';

class App {
    constructor() {
        console.log('App: Initializing...');
        
        // Initialize managers object but don't create instances yet
        this.managers = {};
        
        // Define available manager classes for lazy loading
        this.availableManagers = {
            uiManager: UIManager,
            datePickerManager: DatePickerManager,
            modalManager: ModalManager,
            optionsManager: OptionsManager,
            accordionManager: AccordionManager,
            formManager: FormManager,
            explanationManager: ExplanationManager
        };
        
        this.initialized = false;
        this.coreManagers = ['uiManager'];
        this.requiredManagers = [];
        this.init();
    }

    async init() {
        try {
            console.log('App: Starting initialization...');
            this.getRequiredManagers();
            await this.waitForDependencies();
            this.setupAjax();
            await this.initializeManagers();
            this.initialized = true;
            console.log('App: Initialization complete');
        } catch (error) {
            console.error('App: Initialization failed:', error);
        }
    }

    getRequiredManagers() {
        // Get required managers from window.requiredManagers
        this.requiredManagers = window.requiredManagers || [];
        
        // Add core managers to required managers
        this.requiredManagers = [...new Set([...this.coreManagers, ...this.requiredManagers])];
        
        // Filter out any manager names that don't exist in availableManagers
        this.requiredManagers = this.requiredManagers.filter(managerName => {
            const exists = !!this.availableManagers[managerName];
            if (!exists) {
                console.warn(`App: Manager ${managerName} not found in available managers`);
            }
            return exists;
        });
        
        console.log('App: Required managers:', this.requiredManagers);
    }

    setupAjax() {
        console.log('App: Setting up Ajax...');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    async waitForDependencies() {
        console.log('App: Waiting for dependencies...');
        const maxAttempts = 10;
        const interval = 500;

        for (let attempt = 0; attempt < maxAttempts; attempt++) {
            if (this.checkDependencies()) {
                console.log('App: All dependencies loaded');
                return true;
            }
            console.log(`App: Waiting for dependencies... Attempt ${attempt + 1}/${maxAttempts}`);
            await new Promise(resolve => setTimeout(resolve, interval));
        }
        throw new Error('Dependencies failed to load');
    }

    checkDependencies() {
        const dependencies = {
            jQuery: typeof jQuery !== 'undefined'
        };

        // Only check persianDate if DatePickerManager is required
        if (this.requiredManagers.includes('datePickerManager')) {
            dependencies.persianDate = typeof persianDate !== 'undefined';
            dependencies.persianDatepicker = typeof jQuery !== 'undefined' && 
                                          typeof jQuery.fn.persianDatepicker !== 'undefined';
        }
        
        console.log('App: Dependencies status:', dependencies);
        return Object.values(dependencies).every(dep => dep === true);
    }

    async initializeManagers() {
        console.log('App: Initializing managers...');
        
        // Only initialize managers that are explicitly required
        for (const managerName of this.requiredManagers) {
            console.log(`App: Initializing manager: ${managerName}`);
            
            // Lazy-load the manager instance only when needed
            if (!this.managers[managerName]) {
                const ManagerClass = this.availableManagers[managerName];
                this.managers[managerName] = new ManagerClass();
            }
            
            const manager = this.managers[managerName];
            if (typeof manager.initialize === 'function') {
                await manager.initialize();
            }
        }
    }

    // Get manager method - lazy loads if not already initialized
    getManager(name) {
        if (!this.managers[name] && this.availableManagers[name]) {
            const ManagerClass = this.availableManagers[name];
            this.managers[name] = new ManagerClass();
            
            // Initialize if possible
            if (typeof this.managers[name].initialize === 'function') {
                this.managers[name].initialize();
            }
        }
        return this.managers[name];
    }
    
    // Check if app is initialized
    isInitialized() {
        return this.initialized;
    }
}

// Create and export app instance
let appInstance = null;

function initializeApp() {
    if (!appInstance) {
        appInstance = new App();
        window.app = appInstance;
    }
    return appInstance;
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Always initialize the app since we now have core managers
    const app = initializeApp();
});

export default initializeApp();
