(() => {
    const selector = '.restatify-blog-query--grid';
    const grids = Array.from(document.querySelectorAll(selector));
    const messages = window.RestatifyBlogScroll || {};
    const cacheKey = 'restatify-blog-pages-v1';
    const cacheLifetime = 5 * 60 * 1000;
    const cacheLimit = 6;
    let storageUnavailable = false;

    const readCache = () => {
        if (storageUnavailable) {
            return [];
        }
        try {
            const entries = JSON.parse(sessionStorage.getItem(cacheKey) || '[]');
            return Array.isArray(entries)
                ? entries.filter((entry) => entry && typeof entry.key === 'string'
                    && typeof entry.html === 'string' && typeof entry.time === 'number'
                    && Date.now() >= entry.time && Date.now() - entry.time < cacheLifetime)
                : [];
        } catch (error) {
            storageUnavailable = true;
            console.warn('Restatify blog cache unavailable; using in-memory caching.', error);
            return [];
        }
    };

    const storeCache = (entries) => {
        if (storageUnavailable) {
            return;
        }
        try {
            sessionStorage.setItem(cacheKey, JSON.stringify(entries.slice(-cacheLimit)));
        } catch (error) {
            storageUnavailable = true;
            console.warn('Restatify blog cache unavailable; using in-memory caching.', error);
        }
    };

    const writeCache = (key, html) => {
        const entries = readCache().filter((entry) => entry.key !== key);
        entries.push({ key, html, time: Date.now() });
        storeCache(entries);
    };

    const pageUrl = (href) => {
        if (!href) {
            return '';
        }
        const url = new URL(href, window.location.href);
        if (url.origin !== window.location.origin) {
            throw new Error('Unexpected blog pagination origin');
        }
        url.hash = '';
        return url.href;
    };

    grids.forEach((grid, index) => {
        const list = grid.querySelector('.wp-block-post-template');
        if (!list) {
            return;
        }

        const sentinel = document.createElement('div');
        sentinel.className = 'restatify-blog-scroll';
        const status = document.createElement('span');
        status.setAttribute('role', 'status');
        status.setAttribute('aria-live', 'polite');
        const retry = document.createElement('button');
        retry.type = 'button';
        retry.className = 'restatify-blog-scroll__retry';
        retry.textContent = messages.retry || 'Retry';
        retry.hidden = true;
        sentinel.append(status, retry);
        grid.append(sentinel);

        if (!window.IntersectionObserver || !window.fetch) {
            status.textContent = messages.unsupported || 'Please use a current browser to load more articles.';
            return;
        }

        let next = '';
        let pending = null;
        let appending = false;
        let wantsAppend = false;
        let failed = false;
        const visited = new Set([pageUrl(window.location.href)]);
        const postIds = new Set(Array.from(list.children).flatMap((item) =>
            Array.from(item.classList).filter((name) => /^post-\d+$/.test(name))
        ));

        const showError = (error) => {
            failed = true;
            pending = null;
            list.setAttribute('aria-busy', 'false');
            status.textContent = messages.error || 'More articles could not be loaded.';
            retry.hidden = false;
            console.error('Restatify blog loading failed.', error);
        };

        const nextFrom = (element) => pageUrl(
            element.querySelector('.wp-block-query-pagination-next')?.getAttribute('href')
        );

        const parsePage = (html) => {
            const parsed = new DOMParser().parseFromString(html, 'text/html');
            const result = parsed.querySelector(selector);
            const posts = result?.querySelector('.wp-block-post-template');
            if (!posts || !posts.children.length) {
                throw new Error('Blog response contains no article grid');
            }
            const following = nextFrom(result);
            if (following && (following === next || visited.has(following))) {
                throw new Error('Blog pagination did not advance');
            }
            return { posts, following };
        };

        const loadPage = async (url) => {
            const key = `${index}:${url}`;
            const cached = readCache().find((entry) => entry.key === key);
            if (cached) {
                try {
                    return parsePage(cached.html);
                } catch (error) {
                    console.warn('Restatify blog cache entry invalid; fetching a fresh page.', error);
                    storeCache(readCache().filter((entry) => entry.key !== key));
                }
            }

            const controller = new AbortController();
            const timeout = window.setTimeout(() => controller.abort(), 15000);
            try {
                const response = await fetch(url, {
                    credentials: 'same-origin',
                    signal: controller.signal,
                });
                if (!response.ok) {
                    throw new Error(`Blog request failed: ${response.status}`);
                }
                const parsed = new DOMParser().parseFromString(await response.text(), 'text/html');
                const result = parsed.querySelectorAll(selector)[index];
                if (!result) {
                    throw new Error('Blog response is missing the requested grid');
                }
                const page = parsePage(result.outerHTML);
                // Cache only the cards and next-page link, never forms or the full document.
                const fragment = document.createElement('div');
                fragment.className = 'restatify-blog-query--grid';
                fragment.append(page.posts.cloneNode(true));
                const link = document.createElement('a');
                link.className = 'wp-block-query-pagination-next';
                if (page.following) {
                    link.href = page.following;
                    fragment.append(link);
                }
                writeCache(key, fragment.outerHTML);
                return page;
            } finally {
                window.clearTimeout(timeout);
            }
        };

        const prefetch = () => {
            if (!next || failed) {
                return Promise.resolve(null);
            }
            if (!pending) {
                pending = loadPage(next).then((page) => {
                    page.posts.querySelectorAll('img').forEach((image) => {
                        const preload = new Image();
                        if (image.sizes) {
                            preload.sizes = image.sizes;
                        }
                        if (image.srcset) {
                            preload.srcset = image.srcset;
                        }
                        preload.src = image.src;
                    });
                    return page;
                }).catch((error) => {
                    showError(error);
                    return null;
                });
            }
            return pending;
        };

        const appendPage = async () => {
            if (!next || failed || appending) {
                return;
            }
            appending = true;
            list.setAttribute('aria-busy', 'true');
            status.textContent = messages.loading || 'Loading more articles...';
            const page = await prefetch();
            if (page) {
                visited.add(next);
                Array.from(page.posts.children).forEach((item) => {
                    const id = Array.from(item.classList).find((name) => /^post-\d+$/.test(name));
                    if (!id || !postIds.has(id)) {
                        list.append(item);
                        if (id) {
                            postIds.add(id);
                        }
                    }
                });
                next = page.following;
                pending = null;
                status.textContent = next ? '' : (messages.end || 'All articles are loaded.');
                list.setAttribute('aria-busy', 'false');
                if (!next) {
                    prefetchObserver.disconnect();
                    appendObserver.disconnect();
                } else {
                    prefetch();
                    // Re-evaluate after the sentinel moves (also handles short/duplicate pages).
                    appendObserver.unobserve(sentinel);
                    appendObserver.observe(sentinel);
                }
            }
            appending = false;
        };

        const prefetchObserver = new IntersectionObserver((entries) => {
            if (entries.some((entry) => entry.isIntersecting)) {
                prefetch();
            }
        }, { rootMargin: `${Math.max(window.innerHeight * 2, 1200)}px 0px` });
        const appendObserver = new IntersectionObserver((entries) => {
            wantsAppend = entries.some((entry) => entry.isIntersecting);
            if (wantsAppend) {
                appendPage();
            }
        }, { rootMargin: '300px 0px' });

        retry.addEventListener('click', () => {
            failed = false;
            retry.hidden = true;
            status.textContent = '';
            if (wantsAppend) {
                appendPage();
            } else {
                prefetch();
            }
        });

        try {
            next = nextFrom(grid);
            if (next) {
                prefetchObserver.observe(sentinel);
                appendObserver.observe(sentinel);
            } else {
                status.textContent = messages.end || 'All articles are loaded.';
            }
        } catch (error) {
            showError(error);
        }
    });
})();
