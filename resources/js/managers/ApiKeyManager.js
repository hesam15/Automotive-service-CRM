class ApiKeyManager {
    async getApiKey(e) {
        e.preventDefault();
        
        try {
            const response = await fetch('/dashboard/users/create/apiKey', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                // آپدیت محتوای مودال
                const apiKeyContent = document.getElementById('apiKeyContent');
                if (apiKeyContent) {
                    apiKeyContent.textContent = data.api_key;
                }

                // حذف دکمه از سایدبار
                const apiKeyButton = document.getElementById('apiKeyButton');
                if (apiKeyButton) {
                    apiKeyButton.parentElement.remove();
                }
            } else {
                throw new Error(data.message || 'خطا در دریافت API Key');
            }
        } catch (error) {
            console.error('Error:', error);
            const apiKeyContent = document.getElementById('apiKeyContent');
            if (apiKeyContent) {
                apiKeyContent.innerHTML = `<p class="text-red-500">${error.message || 'خطا در دریافت API Key. لطفا مجددا تلاش کنید.'}</p>`;
            }
        }
    }

    copyApiKey() {
        const apiKeyContent = document.getElementById('apiKeyContent');
        if (apiKeyContent) {
            navigator.clipboard.writeText(apiKeyContent.textContent)
                .then(() => {
                    // نمایش پیام موفقیت‌آمیز بودن کپی
                    const originalText = apiKeyContent.innerHTML;
                    apiKeyContent.innerHTML = '<span class="text-green-600">کپی شد!</span>';
                    setTimeout(() => {
                        apiKeyContent.innerHTML = originalText;
                    }, 1000);
                })
                .catch(err => {
                    console.error('خطا در کپی کردن:', err);
                });
        }
    }
}

// انتقال تمام event listenerها به داخل DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    const apiKeyButton = document.getElementById('apiKeyButton');
    if (apiKeyButton) {
        apiKeyButton.addEventListener('click', (e) => {
            new ApiKeyManager().getApiKey(e);
        });
    }

    // اضافه کردن event listener برای دکمه کپی
    document.addEventListener('click', (e) => {
        if (e.target.closest('.copy-api-key')) {
            new ApiKeyManager().copyApiKey();
        }
    });
});

export default ApiKeyManager;