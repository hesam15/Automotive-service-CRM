import UIManager from './UIManager';

class PhoneVerificationManager {
    constructor() {
        this.config = {
            validation: {
                PHONE_LENGTH: 11,
                CODE_LENGTH: 6,
                TIMEOUT_SECONDS: 120,
                SUCCESS_TIMEOUT: 3000
            },
            endpoints: {
                SEND_CODE: '/send-verification-code',
                VERIFY_CODE: '/verify-code'
            },
            classes: {
                button: {
                    success: ['bg-green-600', 'text-white', 'hover:bg-green-700', 'px-6', 'py-2.5', 'rounded-lg', 'text-sm', 'font-medium', 'transition-colors', 'duration-200'],
                    timer: ['bg-gray-400', 'text-white', 'cursor-not-allowed', 'px-6', 'py-2.5', 'rounded-lg', 'text-sm', 'font-medium'],
                    verify: ['bg-blue-600', 'text-white', 'hover:bg-blue-700', 'px-6', 'py-2.5', 'rounded-lg', 'text-sm', 'font-medium', 'transition-colors', 'duration-200', 'min-w-[120px]']
                },
                input: [
                    'verification-digit', 'w-12', 'h-12', 'text-center', 'text-xl', 'border', 
                    'rounded-lg', 'mx-1', 'focus:border-blue-500', 'focus:ring-blue-500', 
                    'disabled:bg-gray-100', 'disabled:text-gray-500', 'transition-colors', 'duration-200'
                ],
                error: {
                    input: ['border-red-500', 'animate-shake']
                },
                timer: ['text-sm', 'text-gray-600', 'mt-2', 'block', 'text-center']
            },
            messages: {
                sendingCode: 'در حال ارسال...',
                verifyingCode: 'در حال بررسی...',
                verified: 'تایید شد',
                resendCode: 'ارسال مجدد کد',
                waitForCode: 'زمان باقی‌مانده تا ارسال مجدد:'
            },
            animation: {
                duration: 820
            }
        };

        this.state = {
            timers: new Map(),
            loading: false,
            verificationForms: new Map(),
            debounceTimers: new Map()
        };
    }

    init() {
        if (!this.isValidPage()) return;
        this.bindMethods();
        this.setupEventListeners();
    }

    isValidPage() {
        return document.querySelector('.phone-verification-form') !== null;
    }

    bindMethods() {
        this.handleSendCode = this.handleSendCode.bind(this);
        this.handleVerifyCode = this.handleVerifyCode.bind(this);
        this.handleInputChange = this.handleInputChange.bind(this);
        this.handleKeyPress = this.handleKeyPress.bind(this);
        this.startTimer = this.startTimer.bind(this);
        this.updateTimer = this.updateTimer.bind(this);
    }

    setupEventListeners() {
        document.querySelectorAll('.phone-verification-form').forEach(form => {
            const formId = form.getAttribute('id');
            if (!formId) return;

            this.state.verificationForms.set(formId, {
                form,
                phoneInput: form.querySelector('input[name="phone"]'),
                sendButton: form.querySelector('.send-code-btn'),
                successCallback: form.getAttribute('data-success-callback')
            });

            const formElements = this.state.verificationForms.get(formId);

            formElements.phoneInput.addEventListener('input', (e) => this.handleInputChange(e, formId));
            formElements.phoneInput.addEventListener('keypress', this.handleKeyPress);
            formElements.sendButton.addEventListener('click', () => this.handleSendCode(formId));
        });
    }

    createVerificationSection() {
        const section = document.createElement('div');
        section.className = 'verification-section mt-4';
        
        const inputsContainer = document.createElement('div');
        inputsContainer.className = 'flex flex-row-reverse justify-center gap-2 mb-3';
        
        const inputs = Array.from({ length: this.config.validation.CODE_LENGTH }, () => {
            const input = document.createElement('input');
            input.type = 'text';
            input.maxLength = 1;
            input.className = this.config.classes.input.join(' ');
            input.dir = 'ltr';
            return input;
        });
        
        inputs.forEach(input => inputsContainer.appendChild(input));
        
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'flex justify-center';
        
        const verifyButton = document.createElement('button');
        verifyButton.type = 'button';
        verifyButton.className = `verify-code-btn ${this.config.classes.button.verify.join(' ')}`;
        verifyButton.textContent = 'تایید کد';
        
        buttonContainer.appendChild(verifyButton);
        
        const timer = document.createElement('div');
        timer.className = this.config.classes.timer.join(' ');
        
        section.appendChild(inputsContainer);
        section.appendChild(buttonContainer);
        section.appendChild(timer);
        
        return { section, inputs, verifyButton, timer };
    }

    addErrorAnimation(formId) {
        const formElements = this.state.verificationForms.get(formId);
        if (!formElements) return;

        formElements.codeInputs.forEach(input => {
            input.classList.add(...this.config.classes.error.input);
            
            setTimeout(() => {
                input.classList.remove(...this.config.classes.error.input);
            }, this.config.animation.duration);
        });
    }

    handleInputChange(event) {
        const input = event.target;
        const value = input.value.replace(/\D/g, '');
        input.value = value;
    }

    handleKeyPress(event) {
        if (!/[\d]/.test(event.key)) {
            event.preventDefault();
        }
    }

    async handleSendCode(formId) {
        const formElements = this.state.verificationForms.get(formId);
        if (!formElements || this.state.loading) return;

        this.state.loading = true;
        formElements.sendButton.textContent = this.config.messages.sendingCode;
        formElements.sendButton.disabled = true;

        try {
            const response = await fetch(this.config.endpoints.SEND_CODE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ phone: formElements.phoneInput.value })
            });

            const data = await response.json();
            
            if (data.alert) {
                const [message, type] = data.alert;
                UIManager.showSessionAlert(message, type);
            }
            
            if (data.success) {
                const oldSection = formElements.form.querySelector('.verification-section');
                if (oldSection) oldSection.remove();

                const verificationElements = this.createVerificationSection();
                formElements.form.appendChild(verificationElements.section);

                formElements.verificationSection = verificationElements.section;
                formElements.codeInputs = verificationElements.inputs;
                formElements.verifyButton = verificationElements.verifyButton;
                formElements.timerElement = verificationElements.timer;

                formElements.codeInputs.forEach((input, index) => {
                    input.addEventListener('input', (e) => this.handleVerificationInput(e, index, formId));
                    input.addEventListener('keydown', (e) => this.handleVerificationKeydown(e, index, formId));
                });

                formElements.verifyButton.addEventListener('click', () => this.handleVerifyCode(formId));

                this.startTimer(formId);
                formElements.codeInputs[0].focus();
            }
        } catch (error) {
            console.error('Network Error:', error);
            UIManager.showSessionAlert('خطا در ارتباط با سرور', 'danger');
        } finally {
            this.state.loading = false;
            formElements.sendButton.disabled = false;
            formElements.sendButton.textContent = this.config.messages.resendCode;
        }
    }

    async handleVerifyCode(formId) {
        const formElements = this.state.verificationForms.get(formId);
        if (!formElements || this.state.loading) return;

        const code = formElements.codeInputs.map(input => input.value).join('');

        this.state.loading = true;
        formElements.verifyButton.textContent = this.config.messages.verifyingCode;
        formElements.verifyButton.disabled = true;

        try {
            const response = await fetch(this.config.endpoints.VERIFY_CODE, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    phone: formElements.phoneInput.value,
                    code
                })
            });

            const data = await response.json();
            
            if (data.alert) {
                const [message, type] = data.alert;
                UIManager.showSessionAlert(message, type);
            }
            
            if (data.success) {
                this.handleVerificationSuccess(formId);
            } else {
                this.addErrorAnimation(formId);
                formElements.verifyButton.textContent = 'تایید کد';
                formElements.verifyButton.disabled = false;
                
                formElements.codeInputs.forEach(input => {
                    input.value = '';
                });
                formElements.codeInputs[0].focus();
            }
        } catch (error) {
            console.error('Network Error:', error);
            UIManager.showSessionAlert('خطا در ارتباط با سرور', 'danger');
            formElements.verifyButton.textContent = 'تایید کد';
            formElements.verifyButton.disabled = false;
        } finally {
            this.state.loading = false;
        }
    }

    handleVerificationInput(event, index, formId) {
        const formElements = this.state.verificationForms.get(formId);
        if (!formElements) return;

        const input = event.target;
        const value = input.value.replace(/\D/g, '');
        input.value = value.slice(0, 1);

        if (value && index < formElements.codeInputs.length - 1) {
            formElements.codeInputs[index + 1].focus();
        }

        // چک کردن تکمیل بودن همه فیلدها و کلیک خودکار
        if (this.isVerificationCodeComplete(formId)) {
            setTimeout(() => {
                formElements.verifyButton.click();
            }, 300);
        }
    }

    isVerificationCodeComplete(formId) {
        const formElements = this.state.verificationForms.get(formId);
        if (!formElements) return false;

        return formElements.codeInputs.every(input => input.value.length === 1);
    }

    handleVerificationKeydown(event, index, formId) {
        const formElements = this.state.verificationForms.get(formId);
        if (!formElements) return;

        if (event.key === 'Backspace' && !event.target.value && index > 0) {
            formElements.codeInputs[index - 1].focus();
        }
    }

    startTimer(formId) {
        const formElements = this.state.verificationForms.get(formId);
        if (!formElements) return;

        let timeLeft = this.config.validation.TIMEOUT_SECONDS;
        
        if (this.state.timers.has(formId)) {
            clearInterval(this.state.timers.get(formId));
        }

        formElements.sendButton.style.display = 'none';

        this.updateTimer(timeLeft, formId);

        const timer = setInterval(() => {
            timeLeft--;
            this.updateTimer(timeLeft, formId);

            if (timeLeft <= 0) {
                clearInterval(timer);
                formElements.sendButton.style.display = 'block';
                formElements.sendButton.disabled = false;
                this.config.classes.button.timer.forEach(className => {
                    formElements.sendButton.classList.remove(className);
                });
                this.config.classes.button.success.forEach(className => {
                    formElements.sendButton.classList.add(className);
                });
                formElements.sendButton.textContent = this.config.messages.resendCode;
            }
        }, 1000);

        this.state.timers.set(formId, timer);
    }

    updateTimer(seconds, formId) {
        const formElements = this.state.verificationForms.get(formId);
        if (!formElements) return;

        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        formElements.timerElement.textContent = `${this.config.messages.waitForCode} ${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    handleVerificationSuccess(formId) {
        const formElements = this.state.verificationForms.get(formId);
        if (!formElements) return;

        // اضافه کردن hidden input برای حفظ مقدار phone
        const hiddenPhoneInput = document.createElement('input');
        hiddenPhoneInput.type = 'hidden';
        hiddenPhoneInput.name = 'phone';
        hiddenPhoneInput.value = formElements.phoneInput.value;
        formElements.form.appendChild(hiddenPhoneInput);

        formElements.codeInputs.forEach(input => {
            input.disabled = true;
        });

        formElements.verifyButton.disabled = true;
        formElements.verifyButton.textContent = this.config.messages.verified;
        this.config.classes.button.success.forEach(className => {
            formElements.verifyButton.classList.add(className);
        });

        formElements.sendButton.style.display = 'none';

        if (this.state.timers.has(formId)) {
            clearInterval(this.state.timers.get(formId));
            this.state.timers.delete(formId);
        }
        formElements.timerElement.style.display = 'none';

        const phoneInputContainer = formElements.phoneInput.parentElement;
        const verifiedBadge = document.createElement('div');
        verifiedBadge.className = 'verified-badge absolute left-2 top-1/2 -translate-y-1/2 flex items-center opacity-0 transition-opacity duration-200';
        verifiedBadge.innerHTML = `
            <span class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm font-medium">تایید شد</span>
        `;

        formElements.phoneInput.disabled = true;
        formElements.phoneInput.classList.add('bg-gray-50');

        setTimeout(() => {
            if (formElements.verificationSection) {
                formElements.verificationSection.style.transition = 'all 0.3s ease';
                formElements.verificationSection.style.opacity = '0';
                formElements.verificationSection.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    formElements.verificationSection.remove();
                    
                    phoneInputContainer.appendChild(verifiedBadge);
                    setTimeout(() => {
                        verifiedBadge.style.opacity = '1';
                    }, 50);
                }, 300);
            }
        }, 2000);

        if (formElements.successCallback && window[formElements.successCallback]) {
            setTimeout(() => {
                window[formElements.successCallback]();
            }, this.config.validation.SUCCESS_TIMEOUT);
        }
    }

    cleanup() {
        this.state.timers.forEach(timer => clearInterval(timer));
        this.state.timers.clear();
        this.state.debounceTimers.forEach(timer => clearTimeout(timer));
        this.state.debounceTimers.clear();
        this.state.verificationForms.clear();
    }

    destroy() {
        this.cleanup();
    }
}

export default PhoneVerificationManager;
