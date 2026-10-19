import { __ } from '@wordpress/i18n';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	ToggleControl,
	Button,
	ColorPicker,
	RangeControl,
	SelectControl
} from '@wordpress/components';
import { getYouTubeVideoId } from './youtube-url';
import './background-video-controls';

export function clampOverlayOpacity(value) {
	const numberValue = Number(value);

	if (!Number.isFinite(numberValue)) {
		return 0;
	}

	return Math.min(100, Math.max(0, Math.round(numberValue)));
}

export function getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity) {
	if (!overlayEnabled) {
		return undefined;
	}

	return {
		backgroundColor: overlayColor || '#0b1221',
		opacity: clampOverlayOpacity(overlayOpacity) / 100
	};
}

export function getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl) {
	if (!backgroundImageEnabled) {
		return { backgroundImage: 'none' };
	}

	if (backgroundImageUrl) {
		return { backgroundImage: `url(${backgroundImageUrl})` };
	}

	return undefined;
}

export function BackgroundLayoutPanel({ attributes, setAttributes }) {
	const {
		backgroundImageEnabled,
		backgroundImageUrl,
		backgroundMediaType,
		backgroundVideoUrl,
		backgroundVideoPosterUrl,
		parallax,
		fullscreen,
		overlayEnabled,
		overlayColor,
		overlayOpacity
	} = attributes;
	const normalizedOverlayOpacity = clampOverlayOpacity(overlayOpacity);

	return (
		<PanelBody title={__('Background & layout', 'restatify-base')} initialOpen={true}>
			<ToggleControl
				label={__('Enable background media', 'restatify-base')}
				checked={backgroundImageEnabled !== false}
				onChange={(v) => {
					const isEnabled = !!v;
					setAttributes({
						backgroundImageEnabled: isEnabled,
						parallax: isEnabled ? !!parallax : false,
						overlayEnabled: isEnabled ? !!overlayEnabled : false
					});
				}}
			/>
			{backgroundImageEnabled !== false && (
				<>
					<SelectControl
						label={__('Background source', 'restatify-base')}
						value={backgroundMediaType || 'image'}
						options={[
							{ label: __('Image', 'restatify-base'), value: 'image' },
							{ label: __('YouTube video', 'restatify-base'), value: 'video' },
						]}
						onChange={(value) => setAttributes({ backgroundMediaType: value })}
					/>
					{backgroundMediaType === 'video' ? (
						<>
							<TextControl
								label={__('YouTube URL', 'restatify-base')}
								value={backgroundVideoUrl || ''}
								onChange={(value) => setAttributes({ backgroundVideoUrl: value })}
								help={backgroundVideoUrl && !getYouTubeVideoId(backgroundVideoUrl)
									? __('Enter a valid HTTPS YouTube video URL (watch, shorts, embed or youtu.be).', 'restatify-base')
									: __('Muted looping playback starts on the website only after cookie consent. The editor does not connect to YouTube. Reduced-motion visitors see the local fallback.', 'restatify-base')}
							/>
							<p>{__('Video loading image', 'restatify-base')}</p>
							<MediaUploadCheck>
								<MediaUpload
									onSelect={(media) => setAttributes({ backgroundVideoPosterUrl: media?.url || '' })}
									allowedTypes={['image']}
									value={backgroundVideoPosterUrl}
									render={({ open }) => (
										<Button variant="secondary" onClick={open}>
											{backgroundVideoPosterUrl
												? __('Replace video loading image', 'restatify-base')
												: __('Choose video loading image', 'restatify-base')}
										</Button>
									)}
								/>
							</MediaUploadCheck>
							{backgroundVideoPosterUrl && (
								<Button variant="link" onClick={() => setAttributes({ backgroundVideoPosterUrl: '' })}>
									{__('Remove video loading image', 'restatify-base')}
								</Button>
							)}
							<p>{__('Shown while the video loads, before cookie consent and when playback is unavailable. The background overlay also applies to this image.', 'restatify-base')}</p>
						</>
					) : (
						<>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => setAttributes({ backgroundImageUrl: media?.url || '' })}
							allowedTypes={['image']}
							value={backgroundImageUrl}
							render={({ open }) => (
								<Button variant="secondary" onClick={open}>
									{backgroundImageUrl
										? __('Replace background image', 'restatify-base')
										: __('Choose background image from media library', 'restatify-base')}
								</Button>
							)}
						/>
					</MediaUploadCheck>
					{backgroundImageUrl && (
						<Button variant="link" onClick={() => setAttributes({ backgroundImageUrl: '' })}>
							{__('Use theme default background', 'restatify-base')}
						</Button>
					)}
						</>
					)}
				</>
			)}
			<ToggleControl
				label={__('Parallax effect', 'restatify-base')}
				checked={!!parallax}
				disabled={backgroundImageEnabled === false}
				onChange={(v) => setAttributes({ parallax: !!v })}
			/>
			<ToggleControl
				label={__('Fullscreen', 'restatify-base')}
				checked={!!fullscreen}
				onChange={(v) => setAttributes({ fullscreen: !!v })}
			/>
			<ToggleControl
				label={__('Enable background overlay', 'restatify-base')}
				checked={!!overlayEnabled}
				disabled={backgroundImageEnabled === false}
				onChange={(v) => setAttributes({ overlayEnabled: !!v })}
			/>
			{backgroundImageEnabled !== false && overlayEnabled && (
				<>
					<p>{__('Overlay color', 'restatify-base')}</p>
					<ColorPicker
						color={overlayColor || '#0b1221'}
						onChangeComplete={(value) => setAttributes({ overlayColor: value?.hex || '#0b1221' })}
						disableAlpha={true}
					/>
					<RangeControl
						label={__('Overlay opacity (%)', 'restatify-base')}
						value={normalizedOverlayOpacity}
						onChange={(value) => setAttributes({ overlayOpacity: clampOverlayOpacity(value) })}
						min={0}
						max={100}
					/>
					<TextControl
						label={__('Overlay opacity value', 'restatify-base')}
						type="number"
						min={0}
						max={100}
						step={1}
						value={normalizedOverlayOpacity}
						onChange={(value) => setAttributes({ overlayOpacity: clampOverlayOpacity(value) })}
					/>
				</>
			)}
		</PanelBody>
	);
}
