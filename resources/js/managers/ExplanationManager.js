export class ExplanationManager {
    constructor() {
        this.containers = {};
        this.buttons = {};
    }

    initialize() {
        this.setupExplanationToggles();
    }

    setupExplanationToggles() {
        // Define global toggle function
        window.toggleExplanation = (serviceName) => {
            const container = document.getElementById(`explanation_${serviceName}`);
            const button = document.querySelector(`[data-service="${serviceName}"]`);
            
            if (container) {
                this.toggleTextArea(container, button);
            }
        };

        // Setup click handlers for explanation toggle buttons
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

    toggleTextArea(container, button) {
        const existingTextarea = container.querySelector('textarea');
        
        if (existingTextarea) {
            this.removeTextArea(container, button);
        } else {
            this.createTextArea(container, button);
        }
    }

    removeTextArea(container, button) {
        container.innerHTML = '';
        if (button) {
            const icon = button.querySelector('svg');
            if (icon) {
                icon.style.transform = 'rotate(0deg)';
            }
        }
    }

    createTextArea(container, button) {
        const textarea = document.createElement('textarea');
        this.configureTextArea(textarea, container.id);
        
        container.appendChild(textarea);
        
        if (button) {
            const icon = button.querySelector('svg');
            if (icon) {
                icon.style.transform = 'rotate(45deg)';
            }
        }

        // Scroll to the new textarea
        textarea.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    configureTextArea(textarea, containerId) {
        const serviceName = containerId.replace('explanation_', '');
        
        textarea.name = `${serviceName}_explanation`;
        textarea.rows = 3;
        textarea.className = 'w-full px-4 py-2.5 text-sm text-gray-900 rounded-lg border ' +
                           'border-gray-200 focus:border-blue-500 focus:ring-blue-500 ' +
                           'transition-colors duration-200 mb-3';
        textarea.placeholder = 'توضیحات خود را اینجا وارد کنید...';
    }
}