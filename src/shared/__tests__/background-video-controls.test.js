import { getYouTubeVideoId } from '../youtube-url';
import { extendBackgroundAttributes, getVideoBackgroundProps, getVideoPosterBackground } from '../background-video-controls';

jest.mock('@wordpress/compose', () => ({
	createHigherOrderComponent: (factory) => factory,
}));

describe('YouTube background settings and saved markup compatibility', () => {
	it.each([
		'https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=20',
		'https://youtu.be/dQw4w9WgXcQ',
		'https://www.youtube.com/shorts/dQw4w9WgXcQ',
		'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',
	])('accepts a supported URL: %s', (url) => {
		expect(getYouTubeVideoId(url)).toBe('dQw4w9WgXcQ');
	});
	it.each([
		'', 'dQw4w9WgXcQ', 'javascript:alert(1)', 'http://youtu.be/dQw4w9WgXcQ',
		'https://youtube.com.evil.test/watch?v=dQw4w9WgXcQ',
		'https://evil.test/embed/dQw4w9WgXcQ', 'https://youtube.com/watch?v=bad',
		'https://user:pass@youtube.com/watch?v=dQw4w9WgXcQ',
		'https://youtube.com:8080/watch?v=dQw4w9WgXcQ',
	])('rejects unsupported input: %s', (url) => {
		expect(getYouTubeVideoId(url)).toBe('');
	});
	it('extends only Restatify blocks that have shared background controls', () => {
		const attrs = { backgroundImageEnabled: { type: 'boolean', default: true } };
		const settings = { attributes: attrs };
		expect(extendBackgroundAttributes(settings, 'core/cover')).toBe(settings);
		expect(extendBackgroundAttributes({}, 'restatify/test')).toEqual({});
		expect(extendBackgroundAttributes(settings, 'restatify/hero').attributes).toEqual({
			...attrs,
			backgroundMediaType: { type: 'string', default: 'image' },
			backgroundVideoUrl: { type: 'string', default: '' },
			backgroundVideoPosterUrl: { type: 'string', default: '' },
		});
	});
	it.each([{}, { backgroundMediaType: 'image' }, {
		backgroundMediaType: 'video', backgroundImageEnabled: false,
	}])('leaves existing saved props byte-for-byte unchanged: %j', (attributes) => {
		const props = { className: 'restatify-hero-block', style: { backgroundImage: 'url(old.jpg)' } };
		expect(getVideoBackgroundProps(props, attributes)).toBe(props);
	});
	it('saves only a validated video ID while retaining overlay/parallax classes', () => {
		const props = { className: 'restatify-hero-block is-parallax', style: {
			backgroundImage: 'url(old.jpg)', color: 'red',
		} };
		expect(getVideoBackgroundProps(props, {
			backgroundMediaType: 'video', backgroundVideoUrl: 'https://youtu.be/dQw4w9WgXcQ',
			parallax: true,
		})).toEqual({
			className: props.className, style: { color: 'red' },
			'data-restatify-background-video': 'dQw4w9WgXcQ',
			'data-restatify-video-parallax': 'true',
		});
		expect(props.style.backgroundImage).toBe('url(old.jpg)');
	});
	it('does not persist an external embed URL for invalid input', () => {
		expect(getVideoBackgroundProps({}, {
			backgroundMediaType: 'video', backgroundVideoUrl: 'https://evil.test/',
		})['data-restatify-background-video']).toBe('');
	});
	it('uses a separate loading image without changing the image-mode background', () => {
		const props = { style: { backgroundImage: 'url(image-mode.jpg)' } };
		const attributes = {
			backgroundMediaType: 'video',
			backgroundVideoPosterUrl: 'https://example.test/loading.jpg',
		};
		const video = getVideoBackgroundProps(props, attributes);
		expect(video.style.backgroundImage).toBe('url("https://example.test/loading.jpg")');
		expect(video.style['--rs-video-loading-background']).toBe(video.style.backgroundImage);
		expect(getVideoBackgroundProps(props, { ...attributes, backgroundMediaType: 'image' })).toBe(props);
		expect(getVideoBackgroundProps(props, { ...attributes, backgroundImageEnabled: false })).toBe(props);
		expect(getVideoBackgroundProps(props, { ...attributes, backgroundVideoPosterUrl: '' }).style).toEqual({});
	});
	it('quotes image URLs so parentheses and quotes cannot escape the CSS URL', () => {
		const url = 'https://example.test/image (1)"test.jpg';
		expect(getVideoPosterBackground(url)).toBe(`url(${JSON.stringify(url)})`);
		expect(getVideoPosterBackground('  ')).toBeUndefined();
		expect(getVideoPosterBackground(undefined)).toBeUndefined();
	});
});
