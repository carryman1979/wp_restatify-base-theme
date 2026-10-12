describe('consent-gated background video runtime', () => {
	let observer;
	let motion;
	let warn;
	let documentListeners;
	let windowListeners;
	let player;
	const load = () => require('../../../assets/theme/js/background-video.js');
	const visible = async (value = true) => {
		observer.callback([{ target: document.querySelector('section'), isIntersecting: value }]);
		await Promise.resolve();
	};
	const decide = async (state) => {
		document.dispatchEvent(new CustomEvent('restatify:cookie-consent-changed', { detail: { state } }));
		await Promise.resolve();
	};
	const playerEvent = (event, data) => player.events[event]({ target: player, data });
	beforeEach(() => {
		jest.resetModules();
		jest.useFakeTimers();
		document.cookie = 'restatify_cookie_consent=rejected; path=/';
		document.body.innerHTML = '<section data-restatify-background-video="dQw4w9WgXcQ" data-restatify-video-parallax="true"><div class="restatify-hero-bg-overlay" style="opacity:0.4;background-color:red"></div><div class="container">Content</div></section>';
		motion = { matches: false, addEventListener: jest.fn() };
		window.matchMedia = jest.fn(() => motion);
		window.IntersectionObserver = jest.fn(function (callback) {
			observer = this;
			this.callback = callback;
			this.observe = jest.fn();
		});
		window.ResizeObserver = undefined;
		window.requestAnimationFrame = jest.fn();
		window.RestatifyBackgroundVideo = { pause: 'Pause', play: 'Play', error: 'Failed' };
		window.YT = { Player: jest.fn(function (iframe, options) {
			player = this;
			this.events = options.events;
			this.mute = jest.fn();
			this.pauseVideo = jest.fn();
			this.playVideo = jest.fn();
			this.destroy = jest.fn();
		}) };
		warn = jest.spyOn(console, 'warn').mockImplementation(() => {});
		documentListeners = jest.spyOn(document, 'addEventListener');
		windowListeners = jest.spyOn(window, 'addEventListener');
	});
	afterEach(() => {
		// Release old listeners through their consent hook before removing the iframe.
		decide('rejected');
		documentListeners.mock.calls.forEach(([name, callback]) => document.removeEventListener(name, callback));
		windowListeners.mock.calls.forEach(([name, callback]) => window.removeEventListener(name, callback));
		jest.clearAllTimers();
		jest.useRealTimers();
		jest.restoreAllMocks();
	});
	it('makes no YouTube request before consent, even when the section is visible', async () => {
		load();
		await visible();
		expect(document.querySelector('iframe')).toBeNull();
		expect(document.querySelector('.restatify-hero-bg-overlay').style.opacity).toBe('0.4');
	});
	it('creates a muted looping privacy-enhanced player after consent', async () => {
		load();
		await visible();
		await decide('accepted');
		const iframe = document.querySelector('iframe');
		const url = new URL(iframe.src);
		expect(url.origin).toBe('https://www.youtube-nocookie.com');
		expect(url.searchParams.get('mute')).toBe('1');
		expect(url.searchParams.get('playlist')).toBe('dQw4w9WgXcQ');
		expect(url.searchParams.get('loop')).toBe('1');
		expect(url.searchParams.get('origin')).toBe(window.location.origin);
		expect(iframe.tabIndex).toBe(-1);
		expect(iframe.parentElement.getAttribute('aria-hidden')).toBe('true');
		expect(document.querySelector('.restatify-hero-bg-overlay').style.backgroundColor).toBe('red');
	});
	it('defers offscreen embeds even for already-consenting visitors', async () => {
		document.cookie = 'restatify_cookie_consent=accepted; path=/';
		load();
		await visible(false);
		expect(document.querySelector('iframe')).toBeNull();
		await visible();
		expect(document.querySelector('iframe')).not.toBeNull();
	});
	it('destroys the player and controls immediately on consent withdrawal', async () => {
		load();
		await visible();
		await decide('accepted');
		await decide('rejected');
		expect(document.querySelector('iframe')).toBeNull();
		expect(document.querySelector('button')).toBeNull();
		expect(document.querySelector('section').classList.contains('restatify-video-active')).toBe(false);
	});
	it('does not load or animate videos for reduced-motion visitors', async () => {
		motion.matches = true;
		load();
		await visible();
		await decide('accepted');
		expect(document.querySelector('iframe')).toBeNull();
	});
	it('offers pause and play controls through the official player API', async () => {
		load();
		await visible();
		await decide('accepted');
		document.querySelector('button').click();
		expect(document.querySelector('button').textContent).toBe('Play');
		expect(player.pauseVideo).toHaveBeenCalled();
		playerEvent('onStateChange', 1);
		expect(document.querySelector('.is-playing')).not.toBeNull();
		document.querySelector('button').click();
		expect(document.querySelector('button').textContent).toBe('Pause');
	});
	it('retains the local fallback and reports blocked or unavailable videos', async () => {
		load();
		await visible();
		await decide('accepted');
		playerEvent('onError', 101);
		expect(document.querySelector('iframe')).toBeNull();
		expect(document.querySelector('[role="status"]').textContent).toBe('Failed');
		expect(warn).toHaveBeenCalled();
		expect(console).toHaveWarnedWith('Restatify background video unavailable.', 'YouTube player error 101');
	});
	it('reports an autoplay/load timeout rather than leaving an endless loader', async () => {
		load();
		await visible();
		await decide('accepted');
		jest.advanceTimersByTime(20000);
		expect(document.querySelector('iframe')).toBeNull();
		expect(document.querySelector('[role="status"]').textContent).toBe('Failed');
		expect(console).toHaveWarnedWith('Restatify background video unavailable.', 'Player did not start within 20 seconds');
	});
	it('sizes the video as cover with a bounded parallax displacement', async () => {
		document.querySelector('section').getBoundingClientRect = () => ({
			top: -1000, width: 1000, height: 500,
		});
		load();
		await visible();
		await decide('accepted');
		window.requestAnimationFrame.mock.calls.at(-1)[0]();
		const iframe = document.querySelector('iframe');
		expect(Number.parseFloat(iframe.style.height)).toBe(596);
		expect(iframe.style.transform).toBe('translate(-50%, calc(-50% + 48px))');
	});

	it('does not create a player if consent is withdrawn while the API is loading', async () => {
		window.YT = undefined;
		load();
		await visible();
		await decide('accepted');
		expect(document.head.querySelector('script[src="https://www.youtube.com/iframe_api"]')).not.toBeNull();
		await decide('rejected');
		window.YT = { Player: jest.fn() };
		window.onYouTubeIframeAPIReady();
		await Promise.resolve();
		expect(window.YT.Player).not.toHaveBeenCalled();
		expect(document.querySelector('iframe')).toBeNull();
		document.head.querySelectorAll('script[src="https://www.youtube.com/iframe_api"]').forEach((script) => script.remove());
	});

	it('reports an official autoplay-blocked event immediately', async () => {
		load();
		await visible();
		await decide('accepted');
		playerEvent('onAutoplayBlocked');
		expect(document.querySelector('iframe')).toBeNull();
		expect(document.querySelector('[role="status"]').textContent).toBe('Failed');
		expect(console).toHaveWarnedWith('Restatify background video unavailable.', 'Browser blocked autoplay');
	});
});
