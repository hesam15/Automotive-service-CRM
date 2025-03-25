import UIManager from './UIManager';

class FormManager {
    constructor() {
        this.forms = {
            user: {
                create: 'user-create-form',
                edit: 'user-edit-form',
                profile: 'user-profile-form'
            },
            service: {
                create: 'service-create-form',
                edit: 'service-edit-form'
            },
            customer: {
                create: 'customer-create-form',
                edit: 'customer-edit-form'
            }
            // سایر فرم‌ها را اینجا اضافه کنید
        };

        this.messages = {
            user: {
                createSuccess: 'کاربر جدید با موفقیت ایجاد شد',
                updateSuccess: 'اطلاعات کاربر با موفقیت بروزرسانی شد',
                deleteSuccess: 'کاربر با موفقیت حذف شد',
                createError: 'خطا در ایجاد کاربر',
                updateError: 'خطا در بروزرسانی اطلاعات کاربر',
                deleteError: 'خطا در حذف کاربر',
                phoneVerificationError: 'کد احراز هویت تایید نشده است'
            },
            service: {
                createSuccess: 'خدمت جدید با موفقیت ایجاد شد',
                updateSuccess: 'اطلاعات خدمت با موفقیت بروزرسانی شد',
                deleteSuccess: 'خدمت با موفقیت حذف شد',
                createError: 'خطا در ایجاد خدمت',
                updateError: 'خطا در بروزرسانی اطلاعات خدمت',
                deleteError: 'خطا در حذف خدمت'
            },
            customer: {
                createSuccess: 'مشتری جدید با موفقیت ایجاد شد',
                updateSuccess: 'اطلاعات مشتری با موفقیت بروزرسانی شد',
                deleteSuccess: 'مشتری با موفقیت حذف شد',
                createError: 'خطا در ایجاد مشتری',
                updateError: 'خطا در بروزرسانی اطلاعات مشتری',
                deleteError: 'خطا در حذف مشتری'
            },
            general: {
                networkError: 'خطا در ارتباط با سرور',
                validationError: 'لطفاً اطلاعات را به درستی وارد کنید',
                requiredFields: 'لطفاً فیلدهای ضروری را پر کنید',
                saving: 'در حال ذخیره...',
                deleting: 'در حال حذف...',
                updating: 'در حال بروزرسانی...'
            }
        };

        this.initialize();
    }

    initialize() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupFormListeners();
        });
    }

    setupFormListeners() {
        // پیدا کردن تمام فرم‌ها با data attribute
        document.querySelectorAll('[data-form]').forEach(form => {
            const formType = form.dataset.form; // مثلا: user-create, service-edit
            const [module, action] = formType.split('-');

            if (this.forms[module]?.[action]) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleFormSubmit(form, module, action);
                });
            }
        });
    }

    async handleFormSubmit(form, module, action) {
        const submitButton = form.querySelector('button[type="submit"]');
        const loadingMessage = this.messages.general.saving;

        try {
            UIManager.setLoading(submitButton, true, loadingMessage);

            const response = await this.submitForm(form);
            const data = await response.json();

            if (data.success) {
                UIManager.showSuccess(this.messages[module][`${action}Success`]);
                this.handleSuccessResponse(data, module, action);
            } else {
                UIManager.showError(data.message || this.messages[module][`${action}Error`]);
                this.handleValidationErrors(form, data.errors);
            }
        } catch (error) {
            console.error('Form submission error:', error);
            UIManager.showError(this.messages.general.networkError);
        } finally {
            UIManager.setLoading(submitButton, false);
        }
    }

    async submitForm(form) {
        const formData = new FormData(form);
        
        return await fetch(form.action, {
            method: form.method || 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json'
            },
            body: formData
        });
    }

    handleSuccessResponse(data, module, action) {
        if (data.redirect) {
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        }

        if (data.reset) {
            form.reset();
        }

        if (data.callback && typeof window[data.callback] === 'function') {
            window[data.callback](data);
        }
    }

    handleValidationErrors(form, errors) {
        form.querySelectorAll('.error-message').forEach(el => el.remove());

        if (errors) {
            Object.keys(errors).forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    UIManager.showInputError(input, errors[field][0]);
                }
            });
        }
    }

    static confirmDelete(module, callback) {
        UIManager.showConfirm('آیا از حذف این مورد اطمینان دارید؟', async () => {
            try {
                UIManager.showLoading(this.messages.general.deleting);
                await callback();
                UIManager.showSuccess(this.messages[module].deleteSuccess);
            } catch (error) {
                UIManager.showError(this.messages[module].deleteError);
            } finally {
                UIManager.hideLoading();
            }
        });
    }

    static resetForm(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
            form.querySelectorAll('.error-message').forEach(el => el.remove());
        }
    }

    static getFormData(formId) {
        const form = document.getElementById(formId);
        return form ? new FormData(form) : null;
    }
}

// Export singleton instance
export default FormManager;