describe('Restatify Blog Post Grid infinite scrolling', () => {
    let observers;
    let errorLog;
    let warningLog;

    const grid = (ids, next = '') => `
        <div class="wp-block-query restatify-blog-query--grid">
            <ul class="wp-block-post-template">
                ${ids.map((id) => `<li class="wp-block-post post-${id}">
                    <h3>Article ${id}</h3><img src="/image-${id}.jpg" loading="lazy">
                </li>`).join('')}
            </ul>
            <nav class="wp-block-query-pagination">
                ${next ? `<a class="wp-block-query-pagination-next" href="${next}">Next</a>` : ''}
            </nav>
        </div>`;

    const response = (html, status = 200) => ({
        ok: status === 200,
        status,
        text: async () => html,
    });
    const flush = async () => {
        for (let i = 0; i < 12; i++) {
            await Promise.resolve();
        }
    };
    const intersect = async (observerIndex, visible = true) => {
        observers[observerIndex].callback([{ isIntersecting: visible }]);
        await flush();
    };
    const count = () => document.querySelectorAll('.restatify-blog-query--grid .wp-block-post-template > li').length;
    const start = () => require('../../../assets/theme/js/blog-infinite-scroll.js');

    beforeEach(() => {
        jest.resetModules();
        sessionStorage.clear();
        observers = [];
        window.IntersectionObserver = jest.fn(function (callback, options) {
            this.callback = callback;
            this.options = options;
            this.observe = jest.fn();
            this.unobserve = jest.fn();
            this.disconnect = jest.fn();
            observers.push(this);
        });
        window.fetch = jest.fn();
        window.RestatifyBlogScroll = {
            loading: 'Loading', end: 'End', error: 'Failed', retry: 'Retry', unsupported: 'Unsupported',
        };
        errorLog = jest.spyOn(console, 'error').mockImplementation(() => {});
        warningLog = jest.spyOn(console, 'warn').mockImplementation(() => {});
        document.body.innerHTML = grid([1, 2], '?query-21-page=2');
    });

    afterEach(() => {
        jest.restoreAllMocks();
    });

    it('prefetches one page and its images without appending or fetching beyond it', async () => {
        window.fetch.mockResolvedValue(response(grid([3, 4], '?query-21-page=3')));
        const imageSpy = jest.spyOn(window, 'Image');
        start();
        expect(observers[0].options.rootMargin).toBe(`${Math.max(window.innerHeight * 2, 1200)}px 0px`);
        await intersect(0);
        await intersect(0);
        expect(count()).toBe(2);
        expect(window.fetch).toHaveBeenCalledTimes(1);
        expect(imageSpy).toHaveBeenCalledTimes(2);
        expect(sessionStorage.getItem('restatify-blog-pages-v1')).toContain('post-3');
    });

    it('uses the prefetched page on scroll, preloads the following page and stops at the end', async () => {
        window.fetch
            .mockResolvedValueOnce(response(grid([3, 4], '?query-21-page=3')))
            .mockResolvedValueOnce(response(grid([5])));
        start();
        await intersect(0);
        await intersect(1);
        expect(count()).toBe(4);
        expect(window.fetch).toHaveBeenCalledTimes(2);
        await intersect(1);
        expect(count()).toBe(5);
        expect(document.querySelector('[role="status"]').textContent).toBe('End');
        expect(observers.every((observer) => observer.disconnect.mock.calls.length === 1)).toBe(true);
        await intersect(1);
        expect(window.fetch).toHaveBeenCalledTimes(2);
    });

    it('deduplicates concurrent load triggers and keeps the current URL unchanged', async () => {
        let resolve;
        window.fetch.mockReturnValue(new Promise((done) => { resolve = done; }));
        const url = window.location.href;
        start();
        observers[0].callback([{ isIntersecting: true }]);
        observers[1].callback([{ isIntersecting: true }]);
        observers[1].callback([{ isIntersecting: true }]);
        expect(document.querySelector('ul').getAttribute('aria-busy')).toBe('true');
        resolve(response(grid([3])));
        await flush();
        expect(window.fetch).toHaveBeenCalledTimes(1);
        expect(count()).toBe(3);
        expect(window.location.href).toBe(url);
        expect(document.querySelector('ul').getAttribute('aria-busy')).toBe('false');
    });

    it('reuses session-cached cards after revisiting the page', async () => {
        window.fetch.mockResolvedValue(response(grid([3])));
        start();
        await intersect(0);
        expect(window.fetch).toHaveBeenCalledTimes(1);
        jest.resetModules();
        window.fetch.mockClear();
        document.body.innerHTML = grid([1, 2], '?query-21-page=2');
        observers = [];
        start();
        await intersect(1);
        expect(count()).toBe(3);
        expect(window.fetch).not.toHaveBeenCalled();
    });

    it('expires cached pages after five minutes', async () => {
        jest.spyOn(Date, 'now').mockReturnValue(1000000);
        window.fetch.mockResolvedValue(response(grid([3])));
        start();
        await intersect(0);
        expect(window.fetch).toHaveBeenCalledTimes(1);
        Date.now.mockReturnValue(1300001);
        jest.resetModules();
        window.fetch.mockClear();
        document.body.innerHTML = grid([1, 2], '?query-21-page=2');
        observers = [];
        start();
        await intersect(0);
        expect(window.fetch).toHaveBeenCalledTimes(1);
    });

    it('limits persisted cache to six pages', async () => {
        window.fetch.mockImplementation(async (url) => {
            const page = Number(new URL(url).searchParams.get('query-21-page'));
            return response(grid([page + 1], page < 9 ? `?query-21-page=${page + 1}` : ''));
        });
        start();
        for (let page = 2; page <= 9; page++) {
            await intersect(1);
        }
        const cache = JSON.parse(sessionStorage.getItem('restatify-blog-pages-v1'));
        expect(cache).toHaveLength(6);
        expect(count()).toBe(10);
    });

    it('shows failures and retries without losing existing cards', async () => {
        window.fetch
            .mockResolvedValueOnce(response('', 503))
            .mockResolvedValueOnce(response(grid([3])));
        start();
        await intersect(1);
        expect(count()).toBe(2);
        expect(document.querySelector('[role="status"]').textContent).toBe('Failed');
        expect(document.querySelector('button').hidden).toBe(false);
        expect(errorLog).toHaveBeenCalledTimes(1);
        expect(console).toHaveErrored();
        document.querySelector('button').click();
        await flush();
        expect(count()).toBe(3);
        expect(document.querySelector('button').hidden).toBe(true);
    });

    it.each([
        '<html><body>Login page</body></html>',
        grid([3], '?query-21-page=2'),
        grid([], '?query-21-page=3'),
    ])('rejects invalid or non-advancing responses', async (html) => {
        window.fetch.mockResolvedValue(response(html));
        start();
        await intersect(1);
        expect(count()).toBe(2);
        expect(document.querySelector('[role="status"]').textContent).toBe('Failed');
        expect(sessionStorage.getItem('restatify-blog-pages-v1')).toBeNull();
        expect(console).toHaveErrored();
    });

    it('deduplicates posts when publication changes between page requests', async () => {
        window.fetch.mockResolvedValue(response(grid([2, 3])));
        start();
        await intersect(1);
        expect(count()).toBe(3);
        expect(document.querySelectorAll('.post-2')).toHaveLength(1);
    });

    it('isolates multiple grids and leaves Related Articles untouched', async () => {
        document.body.innerHTML = grid([1], '?query-21-page=2')
            + grid([10], '?query-22-page=2')
            + '<div class="restatify-blog-query--related"><ul><li>Related</li></ul></div>';
        window.fetch.mockImplementation(async (url) => response(
            new URL(url).searchParams.has('query-21-page')
                ? grid([2]) + grid([10], '?query-22-page=2')
                : grid([1], '?query-21-page=2') + grid([11])
        ));
        start();
        await intersect(1);
        await intersect(3);
        expect(count()).toBe(4);
        expect(document.querySelector('.restatify-blog-query--related').textContent).toBe('Related');
        expect(window.fetch).toHaveBeenCalledTimes(2);
    });

    it('does not fetch when all articles already fit on the initial page', () => {
        document.body.innerHTML = grid([1]);
        start();
        expect(window.fetch).not.toHaveBeenCalled();
        expect(document.querySelector('[role="status"]').textContent).toBe('End');
    });

    it('does not send requests to external pagination URLs', () => {
        document.body.innerHTML = grid([1], 'https://example.org/page/2/');
        start();
        expect(window.fetch).not.toHaveBeenCalled();
        expect(document.querySelector('[role="status"]').textContent).toBe('Failed');
        expect(console).toHaveErrored();
    });

    it('continues with in-memory prefetch caching if browser storage is blocked', async () => {
        jest.spyOn(Storage.prototype, 'getItem').mockImplementation(() => {
            throw new Error('Storage blocked');
        });
        window.fetch.mockResolvedValue(response(grid([3])));
        start();
        await intersect(0);
        await intersect(1);
        expect(count()).toBe(3);
        expect(window.fetch).toHaveBeenCalledTimes(1);
        expect(warningLog).toHaveBeenCalledTimes(1);
        expect(console).toHaveWarned();
    });

    it('refetches invalid cached cards instead of trapping retries in the cache', async () => {
        sessionStorage.setItem('restatify-blog-pages-v1', JSON.stringify([{
            key: `0:${new URL('?query-21-page=2', window.location.href).href}`,
            html: '<div>No cards</div>',
            time: Date.now(),
        }]));
        window.fetch.mockResolvedValue(response(grid([3])));
        start();
        await intersect(1);
        expect(count()).toBe(3);
        expect(window.fetch).toHaveBeenCalledTimes(1);
        expect(warningLog).toHaveBeenCalledTimes(1);
        expect(errorLog).not.toHaveBeenCalled();
        expect(console).toHaveWarned();
    });

    it('aborts a stalled request after fifteen seconds and exposes retry', async () => {
        jest.useFakeTimers();
        try {
            window.fetch.mockImplementation((url, options) => new Promise((resolve, reject) => {
                options.signal.addEventListener('abort', () => reject(new Error('Timeout')));
            }));
            start();
            observers[1].callback([{ isIntersecting: true }]);
            jest.advanceTimersByTime(15000);
            await flush();
            expect(document.querySelector('[role="status"]').textContent).toBe('Failed');
            expect(document.querySelector('button').hidden).toBe(false);
            expect(count()).toBe(2);
            expect(console).toHaveErrored();
        } finally {
            jest.useRealTimers();
        }
    });

    it('explains unsupported browsers without displaying pagination controls', () => {
        window.IntersectionObserver = undefined;
        start();
        expect(document.querySelector('[role="status"]').textContent).toBe('Unsupported');
        expect(window.fetch).not.toHaveBeenCalled();
    });
});
