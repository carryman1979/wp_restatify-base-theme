describe('comment CAPTCHA frontend', () => {
    let submit;

    beforeEach(() => {
        jest.resetModules();
        jest.useFakeTimers();
        document.body.innerHTML = `<form id="commentform">
            <input name="restatify_comment_captcha_token" value="">
            <p class="restatify-comment-captcha__error" hidden></p>
        </form>`;
        window.RestatifyCommentSecurity = {
            provider: 'recaptcha', siteKey: 'public-key', action: 'restatify_comment', message: 'Try again',
        };
        submit = jest.spyOn(HTMLFormElement.prototype, 'submit').mockImplementation(() => {});
        delete window.grecaptcha;
    });

    afterEach(() => {
        jest.clearAllTimers();
        jest.useRealTimers();
        submit.mockRestore();
        delete window.grecaptcha;
        delete window.RestatifyCommentSecurity;
    });

    const send = () => {
        const event = new Event('submit', { bubbles: true, cancelable: true });
        document.querySelector('form').dispatchEvent(event);
        return event;
    };
    const flush = async () => {
        for (let i = 0; i < 8; i += 1) {
            await Promise.resolve();
        }
    };
    const error = () => document.querySelector('.restatify-comment-captcha__error');

    it('blocks submission when reCAPTCHA is unavailable', () => {
        require('../../../assets/theme/js/comment-security.js');
        expect(send().defaultPrevented).toBe(true);
        expect(error().hidden).toBe(false);
        expect(submit).not.toHaveBeenCalled();
    });

    it('submits only after receiving a token for the configured action', async () => {
        window.grecaptcha = { ready: (callback) => callback(), execute: jest.fn().mockResolvedValue('verified-token') };
        require('../../../assets/theme/js/comment-security.js');
        send();
        await flush();
        expect(window.grecaptcha.execute).toHaveBeenCalledWith('public-key', { action: 'restatify_comment' });
        expect(document.querySelector('input').value).toBe('verified-token');
        expect(submit).toHaveBeenCalledTimes(1);
    });

    it('rejects empty tokens and allows retry after a provider failure', async () => {
        window.grecaptcha = { ready: (callback) => callback(), execute: jest.fn().mockResolvedValue('') };
        require('../../../assets/theme/js/comment-security.js');
        send();
        await flush();
        expect(error().hidden).toBe(false);
        expect(submit).not.toHaveBeenCalled();
        window.grecaptcha.execute.mockResolvedValue('retry-token');
        send();
        await flush();
        expect(submit).toHaveBeenCalledTimes(1);
    });

    it('times out a provider that never completes', async () => {
        window.grecaptcha = { ready: () => {}, execute: jest.fn() };
        require('../../../assets/theme/js/comment-security.js');
        send();
        jest.advanceTimersByTime(12000);
        await flush();
        expect(error().hidden).toBe(false);
        expect(submit).not.toHaveBeenCalled();
    });

    it('requires a Turnstile response but does not intercept a valid response', () => {
        window.RestatifyCommentSecurity.provider = 'turnstile';
        require('../../../assets/theme/js/comment-security.js');
        expect(send().defaultPrevented).toBe(true);
        document.querySelector('input').value = 'turnstile-token';
        expect(send().defaultPrevented).toBe(false);
    });

    it('leaves forms untouched when CAPTCHA is disabled', () => {
        window.RestatifyCommentSecurity.provider = 'none';
        require('../../../assets/theme/js/comment-security.js');
        expect(send().defaultPrevented).toBe(false);
    });
});
