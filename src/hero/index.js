import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck, __experimentalLinkControl as LinkControl } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	ToggleControl,
	Button,
	ColorPicker,
	RangeControl
} from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import './editor.css';
import './style.css';

function clampOverlayOpacity(value) {
	const numberValue = Number(value);

	if (!Number.isFinite(numberValue)) {
		return 0;
	}

	return Math.min(100, Math.max(0, Math.round(numberValue)));
}

function getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity) {
	if (!overlayEnabled) {
		return undefined;
	}

	return {
		backgroundColor: overlayColor || '#0b1221',
		opacity: clampOverlayOpacity(overlayOpacity) / 100
	};
}

function getUrlFromLinkValue(value) {
	if (typeof value === 'string') {
		return value;
	}

	if (value && typeof value.url === 'string') {
		return value.url;
	}

	return '';
}

function Edit({ attributes, setAttributes }) {
	const {
		backgroundImageUrl,
		overlayEnabled,
		overlayColor,
		overlayOpacity,
		parallax,
		fullscreen,
		tagline,
		heading,
		text,
		btn1Label,
		btn1Url,
		btn2Label,
		btn2Url
	} = attributes;
	const heroStyle = backgroundImageUrl ? { backgroundImage: `url(${backgroundImageUrl})` } : undefined;
	const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
	const normalizedOverlayOpacity = clampOverlayOpacity(overlayOpacity);
	const heroClassName = [
		'restatify-hero-block',
		parallax ? 'is-parallax' : '',
		fullscreen ? 'is-fullscreen' : ''
	].join(' ');
	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title={__('Background & layout', 'restatify-base')} initialOpen={true}>
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
						<Button
							variant="link"
							onClick={() => setAttributes({ backgroundImageUrl: '' })}
						>
							{__('Use theme default background', 'restatify-base')}
						</Button>
					)}
					<ToggleControl
						label={__('Parallax effect', 'restatify-base')}
						checked={!!parallax}
						onChange={v => setAttributes({ parallax: !!v })}
					/>
					<ToggleControl
						label={__('Fullscreen', 'restatify-base')}
						checked={!!fullscreen}
						onChange={v => setAttributes({ fullscreen: !!v })}
					/>
					<ToggleControl
						label={__('Enable background overlay', 'restatify-base')}
						checked={!!overlayEnabled}
						onChange={v => setAttributes({ overlayEnabled: !!v })}
					/>
					{overlayEnabled && (
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

				<PanelBody title={__('Content', 'restatify-base')} initialOpen={false}>
					<TextControl
						label={__('Tagline', 'restatify-base')}
						help={__('Short text above the main heading.', 'restatify-base')}
						value={tagline}
						onChange={v => setAttributes({ tagline: v })}
					/>
					<TextControl
						label={__('Heading', 'restatify-base')}
						help={__('Main hero title.', 'restatify-base')}
						value={heading}
						onChange={v => setAttributes({ heading: v })}
					/>
					<TextareaControl
						label={__('Text', 'restatify-base')}
						help={__('Supporting paragraph below the heading.', 'restatify-base')}
						value={text}
						onChange={v => setAttributes({ text: v })}
					/>
					<Button
						variant="secondary"
						onClick={() => setAttributes({ tagline: '', heading: '', text: '' })}
					>
						{__('Reset content', 'restatify-base')}
					</Button>
				</PanelBody>

				<PanelBody title={__('Buttons', 'restatify-base')} initialOpen={false}>
					<TextControl
						label={__('Button 1 label', 'restatify-base')}
						value={btn1Label}
						onChange={v => setAttributes({ btn1Label: v })}
					/>
					<p>{__('Button 1 link', 'restatify-base')}</p>
					<LinkControl
						value={{ url: btn1Url || '' }}
						onChange={(nextValue) => setAttributes({ btn1Url: getUrlFromLinkValue(nextValue) })}
						settings={[]}
					/>
					<TextControl
						label={__('Button 2 label', 'restatify-base')}
						value={btn2Label}
						onChange={v => setAttributes({ btn2Label: v })}
					/>
					<p>{__('Button 2 link', 'restatify-base')}</p>
					<LinkControl
						value={{ url: btn2Url || '' }}
						onChange={(nextValue) => setAttributes({ btn2Url: getUrlFromLinkValue(nextValue) })}
						settings={[]}
					/>
				</PanelBody>
			</InspectorControls>
			<div className={ heroClassName } style={ heroStyle }>
				{overlayEnabled && <div className="restatify-hero-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
				<div className="container">
					<div className="row justify-content-center">
						<div className="col-12 col-lg-11">
							<div className="content-wrapper card-wrap">
								<div className="border-wrap card-wrap"></div>
								<div className="shadow-wrap"></div>
								<div className="content-wrap card-wrap middle-radius">
									<div className="title-wrapper">
										<div className="title-wrap">
											<div className="desc-wrapper">
												<p className="mbr-desc card-wrap mbr-fonts-style display-4">{tagline || __('Add your tagline', 'restatify-base')}</p>
											</div>
											<h2 className="mbr-section-title mbr-fonts-style display-1"><strong>{heading || __('Hero heading', 'restatify-base')}</strong></h2>
											<div className="text-wrapper">
												<p className="mbr-text mbr-fonts-style display-7">{text || __('Add a short supporting text for your offer.', 'restatify-base')}</p>
											</div>
											<div className="frame-wrapper card-bg middle-radius frame_1"><div className="frame-wrap"></div></div>
											<div className="frame-wrapper card-bg middle-radius frame_2"><div className="frame-wrap"></div></div>
										</div>
									</div>
									<div className="card-box">
										<div className="mbr-section-btn">
											<a className="btn btn-black display-7" href={btn1Url || '#'}>{btn1Label || __('Primary action', 'restatify-base')}</a>
											<a className="btn btn-black-outline display-7" href={btn2Url || '#'}>
												<span className="mobi-mbri mobi-mbri-right mbr-iconfont mbr-iconfont-btn"></span>
												{btn2Label || __('Secondary action', 'restatify-base')}
											</a>
										</div>
									</div>
									<div className="line-wrap card-wrap"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}

function Save({ attributes }) {
	const {
		backgroundImageUrl,
		overlayEnabled,
		overlayColor,
		overlayOpacity,
		parallax,
		fullscreen,
		tagline,
		heading,
		text,
		btn1Label,
		btn1Url,
		btn2Label,
		btn2Url
	} = attributes;
	const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
	const blockProps = useBlockProps.save({
		className: [
			'restatify-hero-block',
			parallax ? 'is-parallax' : '',
			fullscreen ? 'is-fullscreen' : ''
		].join(' '),
		style: backgroundImageUrl ? { backgroundImage: `url(${backgroundImageUrl})` } : undefined
	});

	return (
		<div {...blockProps}>
			{overlayEnabled && <div className="restatify-hero-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
			<div className="container">
				<div className="row justify-content-center">
					<div className="col-12 col-lg-11">
						<div className="content-wrapper card-wrap">
							<div className="border-wrap card-wrap"></div>
							<div className="shadow-wrap"></div>
							<div className="content-wrap card-wrap middle-radius">
								<div className="title-wrapper">
									<div className="title-wrap">
										<div className="desc-wrapper">
											<p className="mbr-desc card-wrap mbr-fonts-style display-4">{tagline}</p>
										</div>
										<h2 className="mbr-section-title mbr-fonts-style display-1"><strong>{heading}</strong></h2>
										<div className="text-wrapper">
											<p className="mbr-text mbr-fonts-style display-7">{text}</p>
										</div>
										<div className="frame-wrapper card-bg middle-radius frame_1"><div className="frame-wrap"></div></div>
										<div className="frame-wrapper card-bg middle-radius frame_2"><div className="frame-wrap"></div></div>
									</div>
								</div>
								<div className="card-box">
									<div className="mbr-section-btn">
										{btn1Label && <a className="btn btn-black display-7" href={btn1Url || '#'}>{btn1Label}</a>}
										{btn2Label && (
											<a className="btn btn-black-outline display-7" href={btn2Url || '#'}>
												<span className="mobi-mbri mobi-mbri-right mbr-iconfont mbr-iconfont-btn"></span>
												{btn2Label}
											</a>
										)}
									</div>
								</div>
								<div className="line-wrap card-wrap"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}

const heroIcon = (
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
		<rect x="1" y="1" width="22" height="22" rx="2" ry="2" fill="none" stroke="currentColor" strokeWidth="1.5" />
		<rect x="1" y="1" width="22" height="8" rx="2" ry="2" fill="currentColor" opacity="0.15" />
		<line x1="5" y1="5" x2="14" y2="5" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
		<line x1="5" y1="12.5" x2="19" y2="12.5" stroke="currentColor" strokeWidth="1" strokeLinecap="round" opacity="0.6" />
		<line x1="5" y1="15" x2="16" y2="15" stroke="currentColor" strokeWidth="1" strokeLinecap="round" opacity="0.6" />
		<rect x="5" y="18" width="5" height="2.5" rx="1" fill="currentColor" />
		<rect x="11.5" y="18" width="5" height="2.5" rx="1" fill="currentColor" opacity="0.4" />
	</svg>
);

registerBlockType(metadata.name, {
	...metadata,
	icon: heroIcon,
	edit: Edit,
	save: Save,
});
