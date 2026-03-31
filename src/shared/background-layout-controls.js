import { __ } from '@wordpress/i18n';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	ToggleControl,
	Button,
	ColorPicker,
	RangeControl
} from '@wordpress/components';

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
				label={__('Show background image', 'restatify-base')}
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
