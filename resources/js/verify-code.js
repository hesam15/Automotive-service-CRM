class PhoneVerification {
    constructor(config = {}) {
        const defaultConfig = {
            validation: {
                PHONE_LENGTH: 11,
                CODE_LENGTH: 6,
                TIMEOUT_SECONDS: 120
            },
            endpoints: {
                SEND_CODE: '/send-verification-code',
                VERIFY_CODE: '/verify-code'
            },
            classes: {
                input: 'form-input w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500',
                button: 'px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200',
                successButton: 'bg-blue-600 text-white hover:bg-blue-700',
                timerButton: 'bg-gray-400 text-white cursor-not-allowed',
                verificationInput: 'verification-digit w-12 h-12 text-center text-xl border rounded-lg mx-1 focus:border-blue-500 focus:ring-blue-500',
                timer: 'text-sm text-gray-600 mt-2 block text-center'
            },
            messages: {
                enterPhone: 'لطفا شماره موبایل را وارد کنید',
                invalidPhone: 'شماره موبایل نامعتبر است',
                enterCode: 'لطفا کد تایید را وارد کنید',
                invalidCode: 'کد تایید باید 6 رقم باشد',
                numbersOnly: 'کد تایید باید فقط شامل اعداد باشد',
                verificationSuccess: 'شماره تلفن با موفقیت تایید شد',
                verificationError: 'خطا در تایید کد',
                networkError: 'خطا در ارتباط با سرور',
                sendingCode: 'در حال ارسال...',
                verifyingCode: 'در حال بررسی...',
                verified: 'تایید شد',
                resendCode: 'ارسال مجدد کد',
                waitForCode: 'زمان باقی‌مانده تا ارسال مجدد:'
            }
        };

        this.config = this.mergeConfig(defaultConfig, config);
        this.state = {
            timers: new Map(),
            loading: false,
            verificationForms: new Map(),
            debounceTimers: new Map()
        };

        this.bindMethods();
        this.initialize();
    }

    mergeConfig(defaultConfig, userConfig) {
        return {
            ...defaultConfig,
            ...userConfig,
            validation: { ...defaultConfig.validation, ...userConfig?.validation },
            endpoints: { ...defaultConfig.endpoints, ...userConfig?.endpoints },
            classes: { ...defaultConfig.classes, ...userConfig?.classes },
            messages: { ...defaultConfig.messages, ...userConfig?.messages }
        };
    }

    bindMethods() {
        const methods = [
            'handleVerifyButtonClick',
            'handleVerifyCodeClick',
            'showVerificationForm',
            'startVerificationTimer',
            'resetVerification',
            'handleDigitInput',
            'handleDigitBackspace',
            'updateCompleteVerificationCode',
            'showToast',
            'validatePhoneInput',
            'validateCodeInput',
            'handleVerificationSuccess',
            'setLoading',
            'isValidPage',
            'setupToastContainer',
            'resetButton'
        ];

        methods.forEach(method => {
            if (typeof this[method] === 'function') {
                this[method] = this[method].bind(this);
            }
        });
    }

    initialize() {
        if (!this.isValidPage()) return;
        this.setupEventListeners();
        this.setupToastContainer();
    }

    isValidPage() {
        return document.querySelector('.verify-phone-btn') !== null;
    }

    setupToastContainer() {
        if (!document.getElementById('toast-container')) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed bottom-4 right-4 z-50';
            document.body.appendChild(container);
        }
    }

    setupEventListeners() {
        document.addEventListener('click', this.handleGlobalClick.bind(this));
        document.addEventListener('input', this.handleGlobalInput.bind(this));
        document.addEventListener('keydown', this.handleGlobalKeydown.bind(this));
    }

    handleGlobalClick(e) {
        const verifyBtn = e.target.closest('.verify-phone-btn');
        const codeBtn = e.target.closest('button[id^="verify-code-btn-"]');

        if (verifyBtn) {
            e.preventDefault();
            this.debounce(() => this.handleVerifyButtonClick(verifyBtn), 1000);
        } else if (codeBtn) {
            e.preventDefault();
            this.debounce(() => this.handleVerifyCodeClick(codeBtn), 1000);
        }
    }

    handleGlobalInput(e) {
        const digitInput = e.target.closest('.verification-digit');
        if (digitInput) {
            this.handleDigitInput(digitInput);
        }
    }

    handleGlobalKeydown(e) {
        const digitInput = e.target.closest('.verification-digit');
        if (digitInput && e.key === 'Backspace') {
            this.handleDigitBackspace(digitInput, e);
        }
    }

    debounce(func, wait) {
        const id = Math.random().toString(36).substr(2, 9);
        clearTimeout(this.state.debounceTimers.get(id));
        
        const timeoutId = setTimeout(() => {
            func();
            this.state.debounceTimers.delete(id);
        }, wait);
        
        this.state.debounceTimers.set(id, timeoutId);
    }

    showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `p-4 mb-2 rounded-lg text-white ${
            type === 'error' ? 'bg-red-500' : 'bg-green-500'
        }`;
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    validatePhoneInput(input) {
        if (!input?.value) {
            this.showToast(this.config.messages.enterPhone, 'error');
            return false;
        }
        
        const phoneRegex = /^09[0-9]{9}$/;
        if (!phoneRegex.test(input.value)) {
            this.showToast(this.config.messages.invalidPhone, 'error');
            return false;
        }
        
        return true;
    }

    validateCodeInput(code) {
        if (!code) {
            this.showToast(this.config.messages.enterCode, 'error');
            return false;
        }

        if (code.length !== this.config.validation.CODE_LENGTH) {
            this.showToast(this.config.messages.invalidCode, 'error');
            return false;
        }

        if (!/^\d+$/.test(code)) {
            this.showToast(this.config.messages.numbersOnly, 'error');
            return false;
        }

        return true;
    }

    async handleVerifyButtonClick(button) {
        if (this.state.loading) return;

        const userId = button.dataset.phoneId;
        const phoneInput = document.getElementById(`phone-${userId}`);

        if (!this.validatePhoneInput(phoneInput)) return;

        try {
            this.setLoading(button, true, this.config.messages.sendingCode);
            const response = await this.sendVerificationRequest(phoneInput.value);

            if (response.success) {
                this.showVerificationForm(userId);
                this.startVerificationTimer(userId);
                this.showToast('کد تایید ارسال شد', 'success');
            } else {
                throw new Error(response.message || this.config.messages.verificationError);
            }
        } catch (error) {
            console.error('Send verification error:', error);
            this.showToast(error.message || this.config.messages.networkError, 'error');
            this.resetButton(button);
        } finally {
            this.setLoading(button, false);
        }
    }

    async handleVerifyCodeClick(button) {
        if (this.state.loading) return;

        const userId = button.id.replace('verify-code-btn-', '');
        const form = document.getElementById(`verification-form-${userId}`);
        const code = Array.from(form.querySelectorAll('.verification-digit'))
            .map(input => input.value)
            .join('');
        const phone = document.getElementById(`phone-${userId}`).value;

        if (!this.validateCodeInput(code)) return;

        try {
            this.setLoading(button, true, this.config.messages.verifyingCode);
            const response = await this.sendCodeVerificationRequest(code, phone);

            if (response.success) {
                this.handleVerificationSuccess(userId);
            } else {
                throw new Error(response.message || this.config.messages.verificationError);
            }
        } catch (error) {
            console.error('Verification error:', error);
            this.showToast(error.message || this.config.messages.networkError, 'error');
        } finally {
            this.setLoading(button, false);
        }
    }

    showVerificationForm(userId) {
        const container = document.createElement('div');
        container.id = `verification-form-${userId}`;
        container.className = 'mt-4';
        
        const digitInputs = Array.from({ length: this.config.validation.CODE_LENGTH }, (_, i) => {
            return `<input type="text" maxlength="1" class="verification-digit ${this.config.classes.verificationInput}" data-index="${i}" />`;
        }).join('');

        container.innerHTML = `
            <div class="text-center">
                <div class="flex justify-center gap-2 mb-4">
                    ${digitInputs}
                </div>
                <button id="verify-code-btn-${userId}" class="verify-code-btn ${this.config.classes.button} ${this.config.classes.successButton}">
                    تایید کد
                </button>
                <div id="timer-${userId}" class="${this.config.classes.timer}"></div>
            </div>
        `;

        const existingForm = document.getElementById(`verification-form-${userId}`);
        if (existingForm) {
            existingForm.replaceWith(container);
        } else {
            const phoneInput = document.getElementById(`phone-${userId}`);
            phoneInput.parentNode.insertBefore(container, phoneInput.nextSibling);
        }

        this.state.verificationForms.set(userId, container);
    }

    startVerificationTimer(userId) {
        const timerElement = document.getElementById(`timer-${userId}`);
        let timeLeft = this.config.validation.TIMEOUT_SECONDS;

        if (this.state.timers.has(userId)) {
            clearInterval(this.state.timers.get(userId));
        }

        const timer = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${this.config.messages.waitForCode} ${minutes}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft === 0) {
                clearInterval(timer);
                this.resetVerification(userId);
            }
            timeLeft--;
        }, 1000);

        this.state.timers.set(userId, timer);
    }

    handleDigitInput(input) {
        const value = input.value;
        const index = parseInt(input.dataset.index);
        
        if (value && /^\d$/.test(value)) {
            const nextInput = input.parentElement.querySelector(`[data-index="${index + 1}"]`);
            if (nextInput) {
                nextInput.focus();
            }
        }

        this.updateCompleteVerificationCode(input.closest('[id^="verification-form-"]'));
    }

    handleDigitBackspace(input, event) {
        const index = parseInt(input.dataset.index);
        if (!input.value && index > 0) {
            event.preventDefault();
            const prevInput = input.parentElement.querySelector(`[data-index="${index - 1}"]`);
            if (prevInput) {
                prevInput.focus();
                prevInput.value = '';
            }
        }
    }

    updateCompleteVerificationCode(form) {
        const code = Array.from(form.querySelectorAll('.verification-digit'))
            .map(input => input.value)
            .join('');

        const verifyButton = form.querySelector('.verify-code-btn');
        if (verifyButton) {
            verifyButton.disabled = code.length !== this.config.validation.CODE_LENGTH;
        }
    }

    setLoading(element, loading, text = null) {
        if (!element) return;
        
        this.state.loading = loading;
        element.disabled = loading;
        
        if (loading) {
            element.classList.add('opacity-75', 'cursor-wait');
            if (text) {
                const originalText = element.textContent;
                element.setAttribute('data-original-text', originalText);
                element.textContent = text;
            }
        } else {
            element.classList.remove('opacity-75', 'cursor-wait');
            const originalText = element.getAttribute('data-original-text');
            if (originalText) {
                element.textContent = originalText;
                element.removeAttribute('data-original-text');
            }
        }
    }

    resetButton(button) {
        if (!button) return;
        
        button.disabled = false;
        button.classList.remove('opacity-75', 'cursor-wait');
        const originalText = button.getAttribute('data-original-text');
        if (originalText) {
            button.textContent = originalText;
            button.removeAttribute('data-original-text');
        }
    }

    resetVerification(userId) {
        const form = this.state.verificationForms.get(userId);
        if (form) {
            form.remove();
            this.state.verificationForms.delete(userId);
        }

        if (this.state.timers.has(userId)) {
            clearInterval(this.state.timers.get(userId));
            this.state.timers.delete(userId);
        }
    }

    handleVerificationSuccess(userId) {
        this.showToast(this.config.messages.verificationSuccess, 'success');
        this.resetVerification(userId);
        
        const phoneInput = document.getElementById(`phone-${userId}`);
        const verifyButton = document.querySelector(`[data-phone-id="${userId}"]`);
        
        if (phoneInput) phoneInput.disabled = true;
        if (verifyButton) {
            verifyButton.textContent = this.config.messages.verified;
            verifyButton.disabled = true;
            verifyButton.classList.remove(this.config.classes.successButton);
            verifyButton.classList.add(this.config.classes.timerButton);
        }
    }

    async sendVerificationRequest(phone) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }
    
            const response = await fetch(this.config.endpoints.SEND_CODE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone }),
                credentials: 'same-origin' // اضافه کردن این خط
            });
    
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || this.config.messages.networkError);
            }
    
            return data;
        } catch (error) {
            console.error('Network error:', error);
            throw new Error(error.message || this.config.messages.networkError);
        }
    }

    async sendCodeVerificationRequest(code, phone) {
        try {
            const response = await fetch(this.config.endpoints.VERIFY_CODE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ code, phone })
            });

            if (!response.ok) {
                throw new Error(this.config.messages.networkError);
            }

            return await response.json();
        } catch (error) {
            console.error('Network error:', error);
            throw new Error(this.config.messages.networkError);
        }
    }

    cleanup() {
        this.state.timers.forEach(timer => clearInterval(timer));
        this.state.timers.clear();
        
        this.state.debounceTimers.forEach(timer => clearTimeout(timer));
        this.state.debounceTimers.clear();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.phoneVerification = new PhoneVerification();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.phoneVerification) {
        window.phoneVerification.cleanup();
    }
});