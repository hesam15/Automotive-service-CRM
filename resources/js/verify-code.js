class PhoneVerification {
    constructor(config = {}) {
        const defaultValidation = {
            PHONE_LENGTH: 11,
            CODE_LENGTH: 6,
            TIMEOUT_SECONDS: 500,
        };

        this.config = {
            validation: defaultValidation,
            endpoints: {
                SEND_CODE: '/sendVerify',
                VERIFY_CODE: '/verifyCode'
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
                invalidCode: `کد تایید باید ${defaultValidation.CODE_LENGTH} رقم باشد`,
                numbersOnly: 'کد تایید باید فقط شامل اعداد باشد',
                verificationSuccess: 'شماره تلفن با موفقیت تایید شد',
                verificationError: 'خطا در تایید کد',
                formError: 'خطا در دریافت اطلاعات فرم',
                sendingCode: 'در حال ارسال...',
                verifyingCode: 'در حال بررسی...',
                verified: 'تایید شد',
                resendCode: 'ارسال مجدد کد',
                canResendCode: 'می‌توانید مجدداً درخواست کد کنید',
                waitForCode: 'زمان باقی‌مانده تا ارسال مجدد:'
            },
            ...config
        };

        this.state = {
            timers: new Map(),
            loading: false,
            verificationForms: new Map()
        };

        // First define all methods
        this.showVerificationForm = (userId) => {
            const existingForm = document.getElementById(`verification-form-${userId}`);
            if (existingForm) {
                existingForm.remove();
            }
    
            const phoneInput = document.getElementById(`phone-${userId}`);
            if (!phoneInput) {
                console.error('Phone input not found');
                return;
            }
    
            const form = this.createVerificationForm(userId);
            phoneInput.closest('.relative').insertAdjacentElement('afterend', form);
            this.state.verificationForms.set(userId, form);
        };

        // Then bind all methods
        this.handleVerifyButtonClick = this.handleVerifyButtonClick.bind(this);
        this.handleVerifyCodeClick = this.handleVerifyCodeClick.bind(this);
        this.startVerificationTimer = this.startVerificationTimer.bind(this);
        this.resetVerification = this.resetVerification.bind(this);
        this.handleDigitInput = this.handleDigitInput.bind(this);
        this.handleDigitBackspace = this.handleDigitBackspace.bind(this);
        this.updateCompleteVerificationCode = this.updateCompleteVerificationCode.bind(this);
        this.updateButtonState = this.updateButtonState.bind(this);
        this.resetButton = this.resetButton.bind(this);
        this.showToast = this.showToast.bind(this);
        this.validatePhoneInput = this.validatePhoneInput.bind(this);
        this.validateCodeInput = this.validateCodeInput.bind(this);
        this.handleVerificationSuccess = this.handleVerificationSuccess.bind(this);
        this.setLoading = this.setLoading.bind(this);

        // Initialize after all methods are defined and bound
        this.initialize();
    }

    bindMethods() {
        this.handleVerifyButtonClick = this.handleVerifyButtonClick.bind(this);
        this.handleVerifyCodeClick = this.handleVerifyCodeClick.bind(this);
        this.showVerificationForm = this.showVerificationForm.bind(this);
        this.startVerificationTimer = this.startVerificationTimer.bind(this);
        this.resetVerification = this.resetVerification.bind(this);
        this.handleDigitInput = this.handleDigitInput.bind(this);
    }

    initialize() {
        if (!this.isValidPage()) return;
        this.setupEventListeners();
        this.setupToastContainer();
    }

    setupEventListeners() {
        document.addEventListener('click', (e) => {
            const verifyBtn = e.target.closest('.verify-phone-btn');
            const codeBtn = e.target.closest('button[id^="verify-code-btn-"]');

            if (verifyBtn) {
                e.preventDefault();
                this.handleVerifyButtonClick(verifyBtn);
            } else if (codeBtn) {
                e.preventDefault();
                this.handleVerifyCodeClick(codeBtn);
            }
        });

        document.addEventListener('input', (e) => {
            const digitInput = e.target.closest('.verification-digit');
            if (digitInput) {
                this.handleDigitInput(digitInput);
            }
        });

        document.addEventListener('keydown', (e) => {
            const digitInput = e.target.closest('.verification-digit');
            if (digitInput && e.key === 'Backspace') {
                this.handleDigitBackspace(digitInput, e);
            }
        });
    }

    createVerificationForm(userId) {
        const verificationContainer = document.createElement('div');
        verificationContainer.className = 'mt-4';
        verificationContainer.id = `verification-form-${userId}`;

        let verificationInputs = '';
        for (let i = 0; i < this.config.validation.CODE_LENGTH; i++) {
            verificationInputs += `
                <input type="text" 
                       class="verification-digit w-12 h-12 text-center text-xl border rounded-lg mx-1 focus:border-blue-500 focus:ring-blue-500"
                       maxlength="1"
                       pattern="\\d*"
                       inputmode="numeric"
                       data-index="${i}">
            `;
        }

        verificationContainer.innerHTML = `
            <div class="flex flex-col items-center">
                <div class="verification-inputs flex justify-center mb-4">
                    ${verificationInputs}
                </div>
                <div class="hidden">
                    <input type="hidden" 
                           id="complete-verification-code-${userId}" 
                           name="verification_code">
                </div>
                <button type="button" 
                        id="verify-code-btn-${userId}"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 w-full max-w-xs">
                    تایید کد
                </button>
                <div id="timer-${userId}" class="text-sm text-gray-600 mt-2 block text-center"></div>
            </div>
        `;

        return verificationContainer;
    }

    handleDigitInput(input) {
        const value = input.value.replace(/\D/g, '');
        input.value = value.slice(0, 1);

        if (value && input.nextElementSibling?.classList.contains('verification-digit')) {
            input.nextElementSibling.focus();
        }

        this.updateCompleteVerificationCode(input.closest('.verification-inputs'));
    }

    handleDigitBackspace(input, event) {
        if (!input.value && input.previousElementSibling?.classList.contains('verification-digit')) {
            event.preventDefault();
            input.previousElementSibling.focus();
            input.previousElementSibling.value = '';
        }
    }

    updateCompleteVerificationCode(container) {
        const userId = container.closest('[id^="verification-form-"]').id.replace('verification-form-', '');
        const digits = Array.from(container.querySelectorAll('.verification-digit'))
            .map(input => input.value)
            .join('');
        
        const completeInput = document.getElementById(`complete-verification-code-${userId}`);
        if (completeInput) {
            completeInput.value = digits;
        }
    }

    startVerificationTimer(userId) {
        const timerElement = document.getElementById(`timer-${userId}`);
        const verifyButton = document.querySelector(`.verify-phone-btn[data-phone-id="${userId}"]`);
        
        if (!timerElement || !verifyButton) return;

        let timeLeft = this.config.validation.TIMEOUT_SECONDS;

        if (this.state.timers.has(userId)) {
            clearInterval(this.state.timers.get(userId));
        }

        // Hide the original button during countdown
        verifyButton.style.display = 'none';

        const timer = setInterval(() => {
            timeLeft--;
            
            if (timerElement) {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.innerHTML = `
                    <span class="font-medium">${this.config.messages.waitForCode}</span>
                    <span class="font-bold text-blue-600">
                        ${minutes}:${seconds.toString().padStart(2, '0')}
                    </span>
                `;
            }

            if (timeLeft <= 0) {
                clearInterval(timer);
                // Show the button again with resend text
                verifyButton.style.display = 'block';
                verifyButton.textContent = this.config.messages.resendCode;
                verifyButton.disabled = false;
                verifyButton.classList.remove(...this.config.classes.timerButton.split(' '));
                verifyButton.classList.add(...this.config.classes.successButton.split(' '));
                
                if (timerElement) {
                    timerElement.textContent = '';
                }
            }
        }, 1000);

        this.state.timers.set(userId, timer);
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
                throw new Error(response.message || 'خطا در ارسال کد تایید');
            }
        } catch (error) {
            console.error('Send verification error:', error);
            this.showToast(error.message || 'خطا در ارسال کد تایید', 'error');
            button.style.display = 'block';
        } finally {
            this.setLoading(button, false);
        }
    }

    async handleVerifyCodeClick(button) {
        if (this.state.loading) return;

        const userId = button.id.replace('verify-code-btn-', '');
        const codeInput = document.querySelector(`#verification-form-${userId} input[name="verification_code"]`);
        const phoneInput = document.getElementById(`phone-${userId}`);

        if (!this.validateCodeInput(codeInput)) return;

        try {
            this.setLoading(button, true, this.config.messages.verifyingCode);
            const response = await this.sendCodeVerificationRequest(codeInput.value, phoneInput.value);

            if (response.success) {
                this.handleVerificationSuccess(userId, phoneInput);
            } else {
                throw new Error(response.message || this.config.messages.verificationError);
            }
        } catch (error) {
            console.error('Verification error:', error);
            this.showToast(error.message || this.config.messages.verificationError, 'error');
        } finally {
            this.setLoading(button, false);
        }
    }

    setupToastContainer() {
        const style = document.createElement('style');
        style.textContent = `
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .toast {
                padding: 12px 24px;
                min-width: 300px;
                border-radius: 8px;
                color: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                animation: slideIn 0.3s ease-out;
                direction: rtl;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .toast-success { background-color: #10B981; }
            .toast-error { background-color: #EF4444; }
            .toast-close {
                cursor: pointer;
                opacity: 0.7;
                transition: opacity 0.2s;
            }
            .toast-close:hover { opacity: 1; }
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        const container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    showToast(message, type = 'success') {
        const container = document.querySelector('.toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span>${message}</span>
            <span class="toast-close">×</span>
        `;

        container.appendChild(toast);

        toast.querySelector('.toast-close').addEventListener('click', () => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            toast.addEventListener('animationend', () => toast.remove());
        });

        setTimeout(() => {
            if (toast.isConnected) {
                toast.style.animation = 'slideOut 0.3s ease-out';
                toast.addEventListener('animationend', () => toast.remove());
            }
        }, 5000);
    }

    // Helper methods
    updateButtonState(button, disabled) {
        button.disabled = disabled;
        if (disabled) {
            button.classList.remove(...this.config.classes.successButton.split(' '));
            button.classList.add(...this.config.classes.timerButton.split(' '));
        } else {
            button.classList.remove(...this.config.classes.timerButton.split(' '));
            button.classList.add(...this.config.classes.successButton.split(' '));
        }
    }
    resetButton(button) {
        button.style.display = 'block';
        this.updateButtonState(button, false);
        button.textContent = this.config.messages.resendCode;
    }

    validatePhoneInput(input) {
        if (!input?.value) {
            this.showToast(this.config.messages.enterPhone, 'error');
            return false;
        }
        if (input.value.length !== this.config.validation.PHONE_LENGTH) {
            this.showToast(this.config.messages.invalidPhone, 'error');
            return false;
        }
        return true;
    }

    validateCodeInput(input) {
        if (!input?.value) {
            this.showToast(this.config.messages.enterCode, 'error');
            return false;
        }
        
        if (input.value.length !== this.config.validation.CODE_LENGTH) {
            this.showToast(this.config.messages.invalidCode, 'error');
            return false;
        }

        if (!/^\d+$/.test(input.value)) {
            this.showToast(this.config.messages.numbersOnly, 'error');
            return false;
        }

        return true;
    }

    handleVerificationSuccess(userId, phoneInput) {
        this.showToast(this.config.messages.verificationSuccess, 'success');
        phoneInput.setAttribute('verified', 'true');
        this.resetVerification(userId);
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

    isValidPage() {
        return !!document.querySelector('.verify-phone-btn');
    }

    async sendVerificationRequest(phone) {
        const response = await fetch(this.config.endpoints.SEND_CODE, {
            method: 'POST',
            headers: this.getHeaders(),
            body: `phone=${phone}`
        });
        return await response.json();
    }

    async sendCodeVerificationRequest(code, phone) {
        const response = await fetch(this.config.endpoints.VERIFY_CODE, {
            method: 'POST',
            headers: this.getHeaders(),
            body: `code=${code}&phone=${phone}`
        });
        return await response.json();
    }

    getHeaders() {
        return {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json'
        };
    }

    setLoading(element, loading, text = null) {
        this.state.loading = loading;
        element.disabled = loading;
        if (text) element.textContent = text;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.phoneVerification = new PhoneVerification();
});