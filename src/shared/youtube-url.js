/**
 * Return a video ID only for supported HTTPS YouTube URLs.
 */
export function getYouTubeVideoId(value) {
	if (typeof value !== 'string' || !value.trim()) {
		return '';
	}
	try {
		const url = new URL(value.trim());
		if (url.protocol !== 'https:' || url.username || url.password || url.port) {
			return '';
		}
		let id = '';
		if (url.hostname === 'youtu.be') {
			id = url.pathname.slice(1);
		} else if (['youtube.com', 'www.youtube.com', 'm.youtube.com', 'www.youtube-nocookie.com'].includes(url.hostname)) {
			if (url.pathname === '/watch') {
				id = url.searchParams.get('v') || '';
			} else {
				id = url.pathname.match(/^\/(?:embed|shorts)\/([^/]+)\/?$/)?.[1] || '';
			}
		}
		return /^[a-zA-Z0-9_-]{11}$/.test(id) ? id : '';
	} catch {
		return '';
	}
}
