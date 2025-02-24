// Constants
const VERIFICATION_TIMEOUT = 300; // 5 minutes in seconds
const PHONE_LENGTH = 11;
const VERIFICATION_CODE_LENGTH = 4;
const API_ENDPOINTS = {
    SEND_CODE: 'http://127.0.0.1:8000/sendVerify',
    VERIFY_CODE: 'http://127.0.0.1:8000/verifyCode'
};

class PhoneVerification {
    constructor() {
        this.API_ENDPOINTS = {
            SEND_CODE: '/api/verify/send',
            VERIFY_CODE: '/api/verify/check'
        };
        
        this.PHONE_LENGTH = 11;
        this.VERIFICATION_CODE_LENGTH = 4;
        this.VERIFICATION_TIMEOUT = 300;
        
        this.initialize();
    }

    initialize() {
        if (!this.isValidPage()) return;
        
        this.setupEventListeners();
        this.checkExistingVerification();
    }

    isValidPage() {
        const verifyButton = document.querySelector('.verify-phone-btn');
        if (!verifyButton) {
            console.warn('Verification button not found');
            return false;
        }
        return true;
    }

    setupEventListeners() {
        // Delegate all click events to document
        document.addEventListener('click', (e) => {
            // Handle verify phone button click
            if (e.target.closest('.verify-phone-btn')) {
                e.preventDefault();
                this.handleVerifyButtonClick(e.target.closest('.verify-phone-btn'));
            }
            
            // Handle verify code button click
            if (e.target.matches('[id^="verify-code-btn-"]')) {
                this.handleVerifyCodeClick(e.target);
            }
        });

        // Handle form submission
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
    }

    async handleVerifyButtonClick(button) {
        const userId = button.dataset.phoneId;
        const phoneInput = document.getElementById(`phone-${userId}`);
        
        if (!phoneInput?.value) {
            this.showError('لطفا شماره موبایل را وارد کنید');
            return;
        }

        if (!this.isValidPhone(phoneInput.value)) {
            this.showError('شماره موبایل نامعتبر است');
            return;
        }

        try {
            button.disabled = true;
            button.textContent = 'در حال ارسال...';

            const response = await this.sendVerificationRequest(phoneInput.value);
            
            if (response.success) {
                this.showVerificationForm(userId);
                this.startVerificationTimer(userId);
                this.showSuccess('کد تایید ارسال شد');
            }
        } catch (error) {
            this.showError(error.message);
        } finally {
            button.disabled = false;
            button.textContent = 'ارسال کد تایید';
        }
    }

    async sendVerificationRequest(phone) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!token) throw new Error('CSRF token not found');

        const response = await fetch(this.API_ENDPOINTS.SEND_CODE, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ phone })
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'خطا در ارسال کد');
        }

        return response.json();
    }

    showVerificationForm(userId) {
        const container = document.querySelector(`#phone-${userId}`).parentElement;
        if (!container) return;

        const verificationForm = document.createElement('div');
        verificationForm.id = `verification-form-${userId}`;
        verificationForm.className = 'mt-4';
        verificationForm.innerHTML = `
            <div class="relative">
                <input type="text" 
                       name="verification_code" 
                       maxlength="${this.VERIFICATION_CODE_LENGTH}"
                       class="w-full px-4 py-2 border rounded-lg"
                       placeholder="کد تایید را وارد کنید">
                <button type="button" 
                        id="verify-code-btn-${userId}"
                        class="absolute left-2 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-green-600 text-white rounded-lg">
                    تایید کد
                </button>
            </div>
            <span id="timer-${userId}" class="text-sm text-gray-600 mt-2 block"></span>
        `;

        container.insertAdjacentElement('afterend', verificationForm);
        this.disablePhoneInput(userId);
    }

    startVerificationTimer(userId) {
        const timerElement = document.getElementById(`timer-${userId}`);
        if (!timerElement) return;

        const endTime = Date.now() + (this.VERIFICATION_TIMEOUT * 1000);
        localStorage.setItem('verificationEndTime', endTime.toString());

        const timer = setInterval(() => {
            const remaining = Math.max(0, Math.floor((endTime - Date.now()) / 1000));
            
            if (remaining <= 0) {
                clearInterval(timer);
                this.resetVerification(userId);
                return;
            }

            timerElement.textContent = this.formatTime(remaining);
        }, 1000);
    }

    formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    isValidPhone(phone) {
        return phone && phone.length === this.PHONE_LENGTH;
    }

    showError(message) {
        alert(message); // Replace with your preferred error display method
    }

    showSuccess(message) {
        alert(message); // Replace with your preferred success display method
    }

    resetVerification(userId) {
        const form = document.getElementById(`verification-form-${userId}`);
        if (form) form.remove();

        const phoneInput = document.getElementById(`phone-${userId}`);
        const verifyButton = document.querySelector(`[data-phone-id="${userId}"]`);
        
        if (phoneInput) phoneInput.removeAttribute('readonly');
        if (verifyButton) verifyButton.removeAttribute('disabled');
        
        localStorage.removeItem('verificationEndTime');
    }

    disablePhoneInput(userId) {
        const phoneInput = document.getElementById(`phone-${userId}`);
        const verifyButton = document.querySelector(`[data-phone-id="${userId}"]`);
        
        if (phoneInput) phoneInput.setAttribute('readonly', true);
        if (verifyButton) verifyButton.setAttribute('disabled', true);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    new PhoneVerification();
});