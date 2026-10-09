(() => {
    const config = window.RestatifyCommentSecurity || {};
    const forms = document.querySelectorAll('form#commentform, form.comment-form');

    forms.forEach((form) => {
        const message = form.querySelector('.restatify-comment-captcha__error');
        const tokenField = form.querySelector('[name="restatify_comment_captcha_token"]');
        let submitting = false;

        const showError = () => {
            if (message) {
                message.textContent = config.message || 'Die Sicherheitsprüfung konnte nicht abgeschlossen werden. Bitte versuche es erneut.';
                message.hidden = false;
            }
        };

        form.addEventListener('submit', (event) => {
            if (config.provider === 'turnstile') {
                if (!tokenField || !tokenField.value) {
                    event.preventDefault();
                    showError();
                }
                return;
            }

            if (config.provider !== 'recaptcha') {
                return;
            }

            event.preventDefault();
            if (
                submitting
                || !tokenField
                || !window.grecaptcha
                || typeof window.grecaptcha.ready !== 'function'
                || typeof window.grecaptcha.execute !== 'function'
            ) {
                showError();
                return;
            }

            submitting = true;
            if (message) {
                message.hidden = true;
                message.textContent = '';
            }

            const verification = new Promise((resolve, reject) => {
                try {
                    window.grecaptcha.ready(() => {
                        Promise.resolve(window.grecaptcha.execute(config.siteKey, { action: config.action }))
                            .then(resolve, reject);
                    });
                } catch (error) {
                    reject(error);
                }
            });
            const timeout = new Promise((resolve, reject) => {
                window.setTimeout(() => reject(new Error('CAPTCHA timeout')), 12000);
            });

            Promise.race([verification, timeout]).then((token) => {
                    if (typeof token !== 'string' || token === '') {
                        throw new Error('CAPTCHA token missing');
                    }
                    tokenField.value = token;
                    HTMLFormElement.prototype.submit.call(form);
            }).catch(() => {
                submitting = false;
                showError();
            });
        });
    });
})();
