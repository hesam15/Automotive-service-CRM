export class SelectManager {
    constructor() {
        this.handleSelectAll = this.handleSelectAll.bind(this);
        this.handleCheckboxChange = this.handleCheckboxChange.bind(this);
    }

    async initialize() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initializeElements());
        } else {
            this.initializeElements();
        }
    }

    initializeElements() {
        const elements = {
            selectAllBtn: document.querySelector('#selectAllPermissions'),
            checkboxes: document.querySelectorAll('input[name="permissions[]"]'),
            buttonText: document.querySelector('#selectAllPermissions .select-all-text')
        };

        if (!this.validateElements(elements)) {
            return;
        }

        this.elements = elements;
        this.setupEventListeners();
        this.updateButtonText();
    }

    validateElements(elements) {
        return elements.selectAllBtn && 
               elements.checkboxes.length > 0 && 
               elements.buttonText;
    }

    setupEventListeners() {
        this.elements.selectAllBtn.addEventListener('click', this.handleSelectAll);

        this.elements.checkboxes.forEach((checkbox, index) => {
            checkbox.addEventListener('change', () => this.handleCheckboxChange(index));
        });
    }

    handleSelectAll(event) {
        event.preventDefault();
        event.stopPropagation();
        
        const newState = !this.areAllChecked();
        this.elements.checkboxes.forEach(checkbox => {
            checkbox.checked = newState;
        });

        this.updateButtonText();
    }

    handleCheckboxChange() {
        this.updateButtonText();
    }

    areAllChecked() {
        return Array.from(this.elements.checkboxes)
                    .every(checkbox => checkbox.checked);
    }

    updateButtonText() {
        const allChecked = this.areAllChecked();
        const newText = allChecked ? 'برداشتن همه' : 'انتخاب همه';
        this.elements.buttonText.textContent = newText;
    }
}