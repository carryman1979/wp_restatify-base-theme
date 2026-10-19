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
			backgroundVideoPosterUrl: { type: 'string', default: '' },
		},
	};
}

export function getVideoPosterBackground(url) {
	return typeof url === 'string' && url.trim()
		? `url(${JSON.stringify(url.trim())})`
		: undefined;
}

export function getVideoBackgroundProps(props, attributes) {
	if (attributes.backgroundImageEnabled === false || attributes.backgroundMediaType !== 'video') {
		return props;
	}
	const style = { ...props.style };
	delete style.backgroundImage;
	const poster = getVideoPosterBackground(attributes.backgroundVideoPosterUrl);
	if (poster) {
		style.backgroundImage = poster;
		style['--rs-video-loading-background'] = poster;
	}
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
			const poster = video ? getVideoPosterBackground(props.attributes.backgroundVideoPosterUrl) : undefined;
			return <BlockListBlock {...props} wrapperProps={{
				...props.wrapperProps,
				...(video ? { 'data-restatify-video-preview': 'true' } : {}),
				...(poster ? { style: {
					...props.wrapperProps?.style,
					'--rs-video-loading-background': poster,
				} } : {}),
			}} />;
		}, 'withRestatifyVideoPreview'
	));
}
