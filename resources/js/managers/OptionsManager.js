export class OptionsManager {
    constructor() {
        this.elements = null;
        this.mode = 'multiple'; // Default mode
    }

    initialize() {
        this.initializeOptionsForm();
    }

    initializeOptionsForm() {
        this.elements = {
            addButton: document.getElementById('option_add'),
            removeButton: document.getElementById('option_remove'),
            container: document.getElementById('options_container')
        };

        if (Object.values(this.elements).every(Boolean)) {
            // Detect mode based on data attribute
            this.mode = this.elements.container.dataset.mode || 'multiple';
            this.setupOptionButtons();
            
            // If it's create page and no options exist, add initial option
            if (this.mode === 'single' && !this.elements.container.querySelector('.option-field')) {
                this.elements.container.appendChild(this.createOptionField(0));
            }
        }
    }

    setupOptionButtons() {
        // Only setup add/remove buttons for multiple mode
        if (this.mode === 'multiple') {
            this.elements.addButton.addEventListener('click', () => {
                const newIndex = this.elements.container.querySelectorAll('.option-field').length;
                this.elements.container.appendChild(this.createOptionField(newIndex));
            });

            this.elements.removeButton.addEventListener('click', () => {
                const fields = this.elements.container.getElementsByClassName('option-field');
                if (fields.length > 1) {
                    fields[fields.length - 1].remove();
                }
            });
        } else {
            // Hide add/remove buttons in single mode
            if (this.elements.addButton) this.elements.addButton.style.display = 'none';
            if (this.elements.removeButton) this.elements.removeButton.style.display = 'none';
        }
    }

    createOptionField(index) {
        const div = document.createElement('div');
        div.className = 'option-field grid grid-cols-2 gap-6';
        div.innerHTML = this.getOptionFieldTemplate(index);
        return div;
    }

    getOptionFieldTemplate(index) {
        return `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">خدمات</label>
                <input type="text" name="sub_options[${index}]" placeholder="نام آپشن"
                       class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">مقادیر</label>
                <input type="text" name="sub_values[${index}]" placeholder="مقادیر را با ، جدا کنید"
                       class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
            </div>`;
    }
}