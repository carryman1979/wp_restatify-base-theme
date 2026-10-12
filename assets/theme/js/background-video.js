(() => {
    const blocks = Array.from(document.querySelectorAll('[data-restatify-background-video]'))
        .filter((block) => /^[a-zA-Z0-9_-]{11}$/.test(block.dataset.restatifyBackgroundVideo));
    if (!blocks.length) {
        return;
    }
    const messages = window.RestatifyBackgroundVideo || {};
    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const cookie = document.cookie.match(/(?:^|;\s*)restatify_cookie_consent=([^;]*)/);
    let consent = cookie ? cookie[1] === 'accepted'
        : /(?:^|;\s*)cookiesDirective=[^;]+/.test(document.cookie);
    let frameRequest = 0;
    let apiPromise = null;
    const loadPlayerApi = () => {
        if (window.YT?.Player) {
            return Promise.resolve(window.YT);
        }
        if (!apiPromise) {
            apiPromise = new Promise((resolve, reject) => {
                const previousReady = window.onYouTubeIframeAPIReady;
                const script = document.createElement('script');
                const timeout = window.setTimeout(() => reject(new Error('YouTube API load timeout')), 15000);
                window.onYouTubeIframeAPIReady = () => {
                    window.clearTimeout(timeout);
                    resolve(window.YT);
                    if (typeof previousReady === 'function') {
                        previousReady();
                    }
                };
                script.src = 'https://www.youtube.com/iframe_api';
                script.addEventListener('error', () => {
                    window.clearTimeout(timeout);
                    reject(new Error('YouTube API could not be loaded'));
                }, { once: true });
                document.head.append(script);
            }).catch((error) => {
                apiPromise = null;
                throw error;
            });
        }
        return apiPromise;
    };
    const states = blocks.map((block) => ({
        block, iframe: null, layer: null, button: null, status: null,
        visible: false, paused: false, failed: false, timeout: null,
        player: null, loading: false, generation: 0,
    }));

    const command = (state, func, args = []) => {
        if (typeof state.player?.[func] === 'function') {
            state.player[func](...args);
        }
    };

    const resize = () => {
        frameRequest = 0;
        states.forEach((state) => {
            if (!state.layer) {
                return;
            }
            const rect = state.block.getBoundingClientRect();
            const parallax = state.block.dataset.restatifyVideoParallax === 'true' && !motion.matches;
            const extra = parallax ? 96 : 0;
            const height = Math.max(rect.height + extra, rect.width * 9 / 16);
            const width = Math.max(rect.width, (rect.height + extra) * 16 / 9);
            const offset = parallax
                ? Math.max(-48, Math.min(48, (window.innerHeight / 2 - rect.top - rect.height / 2) * 0.12))
                : 0;
            state.iframe.style.width = `${width}px`;
            state.iframe.style.height = `${height}px`;
            state.iframe.style.transform = `translate(-50%, calc(-50% + ${offset}px))`;
        });
    };
    const scheduleResize = () => {
        if (!frameRequest) {
            frameRequest = window.requestAnimationFrame(resize);
        }
    };

    const remove = (state) => {
        window.clearTimeout(state.timeout);
        state.generation += 1;
        state.loading = false;
        state.player?.destroy();
        state.player = null;
        state.layer?.remove();
        state.button?.remove();
        state.status?.remove();
        state.layer = state.iframe = state.button = state.status = null;
        state.block.classList.remove('restatify-video-active');
    };

    const fail = (state, reason) => {
        remove(state);
        state.failed = true;
        const status = document.createElement('p');
        status.className = 'restatify-background-video__notice';
        status.setAttribute('role', 'status');
        status.textContent = messages.error || 'The background video could not be played.';
        state.status = status;
        state.block.append(status);
        console.warn('Restatify background video unavailable.', reason);
    };

    const start = async (state) => {
        if (!consent || motion.matches || !state.visible || state.iframe || state.failed || state.loading) {
            return;
        }
        state.loading = true;
        const generation = state.generation;
        let api;
        try {
            api = await loadPlayerApi();
        } catch (error) {
            if (generation === state.generation && consent && !motion.matches) {
                fail(state, error.message);
            }
            return;
        }
        if (generation !== state.generation) {
            return;
        }
        state.loading = false;
        if (!consent || motion.matches || !state.visible) {
            return;
        }
        const layer = document.createElement('div');
        layer.className = 'restatify-background-video';
        layer.setAttribute('aria-hidden', 'true');
        const iframe = document.createElement('iframe');
        iframe.title = messages.title || 'Decorative background video';
        iframe.tabIndex = -1;
        iframe.allow = 'autoplay; encrypted-media';
        iframe.referrerPolicy = 'strict-origin-when-cross-origin';
        const url = new URL(`https://www.youtube-nocookie.com/embed/${state.block.dataset.restatifyBackgroundVideo}`);
        url.search = new URLSearchParams({
            autoplay: '1', mute: '1', loop: '1', controls: '0', playsinline: '1',
            playlist: state.block.dataset.restatifyBackgroundVideo,
            enablejsapi: '1', origin: window.location.origin, disablekb: '1', rel: '0',
        }).toString();
        iframe.src = url.href;
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'restatify-background-video__toggle';
        button.textContent = messages.pause || 'Pause background video';
        button.addEventListener('click', () => {
            state.paused = !state.paused;
            command(state, state.paused ? 'pauseVideo' : 'playVideo');
            button.textContent = state.paused
                ? (messages.play || 'Play background video')
                : (messages.pause || 'Pause background video');
        });
        state.layer = layer;
        state.iframe = iframe;
        state.button = button;
        iframe.addEventListener('error', () => fail(state, 'Iframe load failed'));
        layer.append(iframe);
        state.block.prepend(layer);
        state.block.append(button);
        state.block.classList.add('restatify-video-active');
        state.timeout = window.setTimeout(() => fail(state, 'Player did not start within 20 seconds'), 20000);
        state.player = new api.Player(iframe, {
            events: {
                onReady: (event) => {
                    if (generation !== state.generation || !consent) {
                        return;
                    }
                    event.target.mute();
                    if (state.paused || !state.visible) {
                        event.target.pauseVideo();
                        window.clearTimeout(state.timeout);
                    } else {
                        event.target.playVideo();
                    }
                },
                onStateChange: (event) => {
                    if (generation === state.generation && event.data === 1) {
                        window.clearTimeout(state.timeout);
                        state.layer?.classList.add('is-playing');
                    }
                },
                onError: (event) => {
                    if (generation === state.generation) {
                        fail(state, `YouTube player error ${event.data}`);
                    }
                },
                onAutoplayBlocked: () => {
                    if (generation === state.generation) {
                        fail(state, 'Browser blocked autoplay');
                    }
                },
            },
        });
        scheduleResize();
    };

    document.addEventListener('restatify:cookie-consent-changed', (event) => {
        consent = event.detail?.state === 'accepted';
        states.forEach((state) => {
            if (consent) {
                state.failed = false;
                state.status?.remove();
                start(state);
            } else {
                remove(state);
            }
        });
    });
    motion.addEventListener('change', () => {
        states.forEach((state) => motion.matches ? remove(state) : start(state));
    });
    if (window.IntersectionObserver) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                const state = states.find((item) => item.block === entry.target);
                state.visible = entry.isIntersecting;
                if (state.visible) {
                    start(state);
                    if (!state.paused) {
                        command(state, 'playVideo');
                    }
                } else {
                    command(state, 'pauseVideo');
                }
            });
        });
        states.forEach((state) => observer.observe(state.block));
    } else {
        states.forEach((state) => { state.visible = true; start(state); });
    }
    if (window.ResizeObserver) {
        const observer = new ResizeObserver(scheduleResize);
        states.forEach((state) => observer.observe(state.block));
    }
    window.addEventListener('resize', scheduleResize);
    window.addEventListener('scroll', scheduleResize, { passive: true });
})();
