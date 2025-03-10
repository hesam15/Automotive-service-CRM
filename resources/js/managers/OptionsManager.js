// resources/js/managers/OptionsManager.js
export class OptionsManager {
    constructor() {
        this.initialized = false;
        this.modalStates = new Map(); // برای ذخیره وضعیت اولیه هر مودال
        this.eventListeners = new Map(); // برای ذخیره event listeners
    }

    initialize() {
        // برای صفحه create
        this.initializeCreatePage();

        // برای صفحه index (edit modals)
        this.initializeEditModals();

        // اضافه کردن event listener برای بستن مودال
        this.setupModalCloseListeners();

        this.initialized = true;
    }

    initializeCreatePage() {
        const createContainer = document.querySelector('.option-values-container');
        const createAddBtn = document.querySelector('.option-add');
        const createRemoveBtn = document.querySelector('.option-remove');

        if (createContainer && createAddBtn && createRemoveBtn) {
            this.setupButtons(createContainer, createAddBtn, createRemoveBtn, false);
            this.updateButtonsVisibility(createContainer, createAddBtn, createRemoveBtn);
        }
    }

    initializeEditModals() {
        document.querySelectorAll('[id^="optionEditModal-"]').forEach(modal => {
            const container = modal.querySelector('.option-values-container');
            const addBtn = modal.querySelector('.option-add-btn');
            const removeBtn = modal.querySelector('.option-remove-btn');

            if (container && addBtn && removeBtn) {
                // ذخیره وضعیت اولیه مودال
                this.saveModalInitialState(modal.id, container.innerHTML);
                
                this.setupButtons(container, addBtn, removeBtn, true);
                this.updateButtonsVisibility(container, addBtn, removeBtn);
            }
        });
    }

    setupButtons(container, addBtn, removeBtn, isModal) {
        // حذف event listener های قبلی
        this.removeOldEventListeners(addBtn, removeBtn);

        // ایجاد handler های جدید
        const addHandler = () => {
            const fieldsCount = container.querySelectorAll('.option-field').length;
            
            if (fieldsCount < 10) {
                container.appendChild(this.createOptionField());
                this.updateButtonsVisibility(container, addBtn, removeBtn);
            }
        };

        const removeHandler = () => {
            const fields = container.querySelectorAll('.option-field');
            
            if (fields.length > 1) {
                fields[fields.length - 1].remove();
                this.updateButtonsVisibility(container, addBtn, removeBtn);
            }
        };

        // ذخیره handler ها برای حذف بعدی
        this.eventListeners.set(addBtn, addHandler);
        this.eventListeners.set(removeBtn, removeHandler);

        // اضافه کردن event listener های جدید
        addBtn.addEventListener('click', addHandler);
        removeBtn.addEventListener('click', removeHandler);
    }

    removeOldEventListeners(addBtn, removeBtn) {
        // حذف event listener قبلی از دکمه اضافه
        if (this.eventListeners.has(addBtn)) {
            const oldAddHandler = this.eventListeners.get(addBtn);
            addBtn.removeEventListener('click', oldAddHandler);
        }

        // حذف event listener قبلی از دکمه حذف
        if (this.eventListeners.has(removeBtn)) {
            const oldRemoveHandler = this.eventListeners.get(removeBtn);
            removeBtn.removeEventListener('click', oldRemoveHandler);
        }
    }

    createOptionField() {
        const field = document.createElement('div');
        field.className = 'option-field grid grid-cols-2 gap-6 mb-6';
        field.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نام خدمت</label>
                <input type="text" 
                    name="options[]" 
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">مقادیر</label>
                <input type="text" 
                    name="values[]" 
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="مقادیر را با کاما جدا کنید">
            </div>
        `;
        return field;
    }

    saveModalInitialState(modalId, content) {
        this.modalStates.set(modalId, content);
    }

    resetModalState(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const container = modal.querySelector('.option-values-container');
        const initialState = this.modalStates.get(modalId);

        if (container && initialState) {
            // حذف event listener های قبلی
            const addBtn = modal.querySelector('.option-add-btn');
            const removeBtn = modal.querySelector('.option-remove-btn');
            if (addBtn && removeBtn) {
                this.removeOldEventListeners(addBtn, removeBtn);
            }

            // بازگرداندن HTML اولیه
            container.innerHTML = initialState;
            
            // اضافه کردن event listener های جدید
            if (addBtn && removeBtn) {
                this.setupButtons(container, addBtn, removeBtn, true);
                this.updateButtonsVisibility(container, addBtn, removeBtn);
            }
        }

        // ریست کردن فیلد نام
        const nameInput = modal.querySelector('input[name="name"]');
        if (nameInput) {
            nameInput.value = nameInput.defaultValue;
        }
    }

    setupModalCloseListeners() {
        // برای دکمه‌های بستن مودال
        document.querySelectorAll('.modal-close').forEach(closeBtn => {
            closeBtn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal');
                if (modal) {
                    this.resetModalState(modal.id);
                }
            });
        });

        // برای کلیک روی backdrop
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.resetModalState(modal.id);
                }
            });
        });

        // برای دکمه ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal:not(.hidden)');
                if (openModal) {
                    this.resetModalState(openModal.id);
                }
            }
        });
    }

    updateButtonsVisibility(container, addBtn, removeBtn) {
        if (!container || !addBtn || !removeBtn) return;

        const fields = container.querySelectorAll('.option-field');
        const fieldsCount = fields.length;

        // مدیریت دکمه حذف
        removeBtn.style.display = fieldsCount > 1 ? 'inline-flex' : 'none';

        // مدیریت دکمه اضافه
        addBtn.style.display = fieldsCount >= 10 ? 'none' : 'inline-flex';
    }
}