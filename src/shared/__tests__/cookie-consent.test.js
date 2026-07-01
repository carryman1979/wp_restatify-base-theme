describe('cookie-consent booking guard', () => {
    beforeEach(() => {
        jest.resetModules();
        document.body.innerHTML = '';
        window.location.hash = '';
        delete window.__RS_COOKIE_CONSENT_TEST__;
    });

    function loadHooks() {
        require('../../../assets/theme/js/cookie-consent.js');
        return window.__RS_COOKIE_CONSENT_TEST__;
    }

    it('returns true when booking hash is present', () => {
        window.location.hash = '#restatify-booking';
        const hooks = loadHooks();

        expect(hooks.isBookingRequestedOrOpen()).toBe(true);
    });

    it('returns true when booking overlay is open', () => {
        document.body.innerHTML = '<div class="restatify-booking__overlay"></div>';
        const hooks = loadHooks();

        expect(hooks.isBookingRequestedOrOpen()).toBe(true);
    });

    it('returns false when no booking hash and no open overlay exists', () => {
        document.body.innerHTML = '<div class="restatify-booking__overlay" hidden></div>';
        const hooks = loadHooks();

        expect(hooks.isBookingRequestedOrOpen()).toBe(false);
    });

    it('dispatches restatify:cookie-consent-changed event with accepted state', () => {
        const hooks = loadHooks();
        const events = [];

        document.addEventListener('restatify:cookie-consent-changed', (event) => {
            events.push(event.detail);
        });

        hooks.dispatchConsentChanged('accepted');

        expect(events).toHaveLength(1);
        expect(events[0]).toEqual({
            state: 'accepted',
            accepted: true,
            rejected: false,
            pending: false
        });
    });
});
