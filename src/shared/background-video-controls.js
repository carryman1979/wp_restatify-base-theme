import { addFilter, hasFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { getYouTubeVideoId } from './youtube-url';
import './background-video.css';

const namespace = 'restatify/background-video';

export function extendBackgroundAttributes(settings, name = settings.name || '') {
	if (!name.startsWith('restatify/') || !settings.attributes?.backgroundImageEnabled) {
		return settings;
	}
	return {
		...settings,
		attributes: {
			...settings.attributes,
			backgroundMediaType: { type: 'string', default: 'image' },
			backgroundVideoUrl: { type: 'string', default: '' },
		},
	};
}

export function getVideoBackgroundProps(props, attributes) {
	if (attributes.backgroundImageEnabled === false || attributes.backgroundMediaType !== 'video') {
		return props;
	}
	const style = { ...props.style };
	// In video mode only the local theme default remains as the consent/error fallback.
	delete style.backgroundImage;
	return {
		...props,
		style,
		'data-restatify-background-video': getYouTubeVideoId(attributes.backgroundVideoUrl),
		'data-restatify-video-parallax': attributes.parallax ? 'true' : 'false',
	};
}

if (!hasFilter('blocks.registerBlockType', namespace)) {
	addFilter('blocks.registerBlockType', namespace, extendBackgroundAttributes);
	addFilter('blocks.getSaveContent.extraProps', namespace, (props, blockType, attributes) =>
		blockType.name.startsWith('restatify/') ? getVideoBackgroundProps(props, attributes) : props
	);
	addFilter('editor.BlockListBlock', namespace, createHigherOrderComponent((BlockListBlock) =>
		(props) => {
			const video = props.name.startsWith('restatify/')
				&& props.attributes.backgroundImageEnabled !== false
				&& props.attributes.backgroundMediaType === 'video';
			return <BlockListBlock {...props} wrapperProps={{
				...props.wrapperProps,
				...(video ? { 'data-restatify-video-preview': 'true' } : {}),
			}} />;
		}, 'withRestatifyVideoPreview'
	));
}
