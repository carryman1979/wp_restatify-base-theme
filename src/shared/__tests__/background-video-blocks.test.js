import { getBlockType, getSaveContent } from '@wordpress/blocks';

jest.mock('@wordpress/blocks', () => {
	const { applyFilters } = require('@wordpress/hooks');
	const { cloneElement, renderToString } = require('@wordpress/element');
	const registry = {};
	return {
		registerBlockType: (name, settings) => {
			registry[name] = { ...applyFilters('blocks.registerBlockType', settings, name), name };
		},
		getBlockType: (name) => registry[name],
		getSaveContent: (block, attributes) => {
			const element = block.save({ attributes });
			return renderToString(cloneElement(element,
				applyFilters('blocks.getSaveContent.extraProps', element.props, block, attributes)
			));
		},
	};
}, { virtual: true });

jest.mock('@wordpress/block-editor', () => ({
	useBlockProps: Object.assign(() => ({}), { save: (props) => props }),
	RichText: { Content: ({ tagName: Tag = 'div', value, ...props }) => <Tag {...props}>{value}</Tag> },
}), { virtual: true });
jest.mock('@wordpress/components', () => ({}), { virtual: true });

const names = [
	'hero', 'services', 'studies', 'testimonials', 'mission', 'metrics', 'insight',
	'gallery', 'faq', 'pricing', 'contact', 'clients', 'oracle', 'timer',
];
names.forEach((name) => require(`../../${name}/index.js`));

describe('background video wiring across all custom sections', () => {
	it.each(names)('%s registers shared attributes and preserves image output', (name) => {
		const block = getBlockType(`restatify/${name}`);
		expect(block.attributes.backgroundMediaType.default).toBe('image');
		expect(block.attributes.backgroundVideoUrl.default).toBe('');
		const attributes = Object.fromEntries(Object.entries(block.attributes).map(([key, schema]) =>
			[key, schema.default]
		));
		attributes.backgroundImageUrl = 'https://example.test/background.jpg';
		attributes.overlayEnabled = true;
		attributes.overlayOpacity = 35;
		attributes.parallax = true;
		delete attributes.backgroundMediaType;
		delete attributes.backgroundVideoUrl;
		const legacy = getSaveContent(block, attributes);
		expect(legacy).toContain('background-image:url(https://example.test/background.jpg)');
		expect(getSaveContent(block, {
			...attributes, backgroundMediaType: 'image', backgroundVideoUrl: 'https://youtu.be/dQw4w9WgXcQ',
		})).toBe(legacy);
		const video = getSaveContent(block, {
			...attributes, backgroundMediaType: 'video', backgroundVideoUrl: 'https://youtu.be/dQw4w9WgXcQ',
		});
		expect(video).toContain('data-restatify-background-video="dQw4w9WgXcQ"');
		expect(video).toContain('data-restatify-video-parallax="true"');
		expect(video).toContain('opacity:0.35');
		expect(video).not.toContain('background-image:url(');
		expect(video).not.toContain('<iframe');
		expect(video).not.toContain('youtu.be');
	});
});
