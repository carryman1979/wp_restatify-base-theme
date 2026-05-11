import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	PlainText,
	MediaUpload,
	MediaUploadCheck,
	__experimentalLinkControl as LinkControl
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
	ToggleControl
} from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { BackgroundLayoutPanel, getBackgroundStyle, getOverlayStyle } from '../shared/background-layout-controls';
import { getUrlFromLinkValue } from '../shared/link-utils';
import { getThemeColorPresets, ThemeColorControl } from '../shared/theme-color-controls';
import './editor.css';
import './style.css';

function getCtaColorVariables(attributes) {
	const {
		ctaLightNormalColor,
		ctaLightHighlightColor,
		ctaDarkNormalColor,
		ctaDarkHighlightColor
	} = attributes;
	const style = {};

	if (ctaLightNormalColor) {
		style['--rs-services-cta-light-normal'] = ctaLightNormalColor;
	}

	if (ctaLightHighlightColor) {
		style['--rs-services-cta-light-highlight'] = ctaLightHighlightColor;
	}

	if (ctaDarkNormalColor) {
		style['--rs-services-cta-dark-normal'] = ctaDarkNormalColor;
	}

	if (ctaDarkHighlightColor) {
		style['--rs-services-cta-dark-highlight'] = ctaDarkHighlightColor;
	}

	return style;
}

function Edit({ attributes, setAttributes }) {
	const {
		backgroundImageEnabled,
		backgroundImageUrl,
		overlayEnabled,
		overlayColor,
		overlayOpacity,
		parallax,
		fullscreen,
		label,
		showLabel,
		heading,
		showHeading,
		item1Title,
		showItem1Title,
		item1Text,
		showItem1Text,
		item2Title,
		showItem2Title,
		item2Text,
		showItem2Text,
		item3Title,
		showItem3Title,
		item3Text,
		showItem3Text,
		imageUrl,
		ctaLabel,
		showCtaLabel,
		ctaUrl,
		ctaLightNormalColor,
		ctaLightHighlightColor,
		ctaDarkNormalColor,
		ctaDarkHighlightColor
	} = attributes;

	const blockClassName = [
		'restatify-services-block',
		backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
		fullscreen ? 'is-fullscreen' : ''
	].join(' ');

	const blockStyle = getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl);

	const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
	const imageFallbackText = __('Choose image', 'restatify-base');
	const ctaColorVariables = getCtaColorVariables(attributes);
	const normalizedCtaUrl = ctaUrl === '#' ? '' : ctaUrl;
	const themeColorPresets = useMemo(() => getThemeColorPresets(), []);

	return (
		<div {...useBlockProps()}>
			<InspectorControls>
				<BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes} />

				<PanelBody title={__('Content', 'restatify-base')} initialOpen={false}>
					<TextControl
						label={__('Label', 'restatify-base')}
						value={label}
						onChange={(v) => setAttributes({ label: v })}
					/>
					<TextareaControl
						label={__('Heading', 'restatify-base')}
						value={heading}
						onChange={(v) => setAttributes({ heading: v })}
					/>
				</PanelBody>

				<PanelBody title={__('Feature items', 'restatify-base')} initialOpen={false}>
					<TextControl
						label={__('Item 1 title', 'restatify-base')}
						value={item1Title}
						onChange={(v) => setAttributes({ item1Title: v })}
					/>
					<TextareaControl
						label={__('Item 1 text', 'restatify-base')}
						value={item1Text}
						onChange={(v) => setAttributes({ item1Text: v })}
					/>
					<TextControl
						label={__('Item 2 title', 'restatify-base')}
						value={item2Title}
						onChange={(v) => setAttributes({ item2Title: v })}
					/>
					<TextareaControl
						label={__('Item 2 text', 'restatify-base')}
						value={item2Text}
						onChange={(v) => setAttributes({ item2Text: v })}
					/>
					<TextControl
						label={__('Item 3 title', 'restatify-base')}
						value={item3Title}
						onChange={(v) => setAttributes({ item3Title: v })}
					/>
					<TextareaControl
						label={__('Item 3 text', 'restatify-base')}
						value={item3Text}
						onChange={(v) => setAttributes({ item3Text: v })}
					/>
				</PanelBody>

				<PanelBody title={__('Image & button', 'restatify-base')} initialOpen={false}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => setAttributes({ imageUrl: media?.url || '' })}
							allowedTypes={['image']}
							value={imageUrl}
							render={({ open }) => (
								<Button variant="secondary" onClick={open}>
									{imageUrl ? __('Replace card image', 'restatify-base') : __('Choose card image', 'restatify-base')}
								</Button>
							)}
						/>
					</MediaUploadCheck>
					{imageUrl && (
						<Button variant="link" onClick={() => setAttributes({ imageUrl: '' })}>
							{__('Remove card image', 'restatify-base')}
						</Button>
					)}
					<TextControl
						label={__('Button label', 'restatify-base')}
						value={ctaLabel}
						onChange={(v) => setAttributes({ ctaLabel: v })}
					/>
					<p>{__('Button link', 'restatify-base')}</p>
					<LinkControl
						value={{ url: normalizedCtaUrl || '' }}
						onChange={(nextValue) => setAttributes({ ctaUrl: getUrlFromLinkValue(nextValue) })}
						settings={[]}
					/>
				</PanelBody>

				<PanelBody title={__('Button color overrides (optional)', 'restatify-base')} initialOpen={false}>
					<p>{__('Light theme button colors', 'restatify-base')}</p>
					<ThemeColorControl
						label={__('Normal color', 'restatify-base')}
						value={ctaLightNormalColor}
						onChange={(nextColor) => setAttributes({ ctaLightNormalColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<ThemeColorControl
						label={__('Highlight color', 'restatify-base')}
						value={ctaLightHighlightColor}
						onChange={(nextColor) => setAttributes({ ctaLightHighlightColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<p>{__('Dark theme button colors', 'restatify-base')}</p>
					<ThemeColorControl
						label={__('Normal color', 'restatify-base')}
						value={ctaDarkNormalColor}
						onChange={(nextColor) => setAttributes({ ctaDarkNormalColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<ThemeColorControl
						label={__('Highlight color', 'restatify-base')}
						value={ctaDarkHighlightColor}
						onChange={(nextColor) => setAttributes({ ctaDarkHighlightColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<Button
						variant="link"
						onClick={() => setAttributes({
							ctaLightNormalColor: '',
							ctaLightHighlightColor: '',
							ctaDarkNormalColor: '',
							ctaDarkHighlightColor: ''
						})}
					>
						{__('Reset button colors to default', 'restatify-base')}
					</Button>
				</PanelBody>

				<PanelBody title={__('Content visibility', 'restatify-base')} initialOpen={false}>
					<ToggleControl
						label={__('Show label', 'restatify-base')}
						checked={showLabel !== false}
						onChange={(v) => setAttributes({ showLabel: !!v })}
					/>
					<ToggleControl
						label={__('Show heading', 'restatify-base')}
						checked={showHeading !== false}
						onChange={(v) => setAttributes({ showHeading: !!v })}
					/>
					<ToggleControl
						label={__('Show item 1 title', 'restatify-base')}
						checked={showItem1Title !== false}
						onChange={(v) => setAttributes({ showItem1Title: !!v })}
					/>
					<ToggleControl
						label={__('Show item 1 text', 'restatify-base')}
						checked={showItem1Text !== false}
						onChange={(v) => setAttributes({ showItem1Text: !!v })}
					/>
					<ToggleControl
						label={__('Show item 2 title', 'restatify-base')}
						checked={showItem2Title !== false}
						onChange={(v) => setAttributes({ showItem2Title: !!v })}
					/>
					<ToggleControl
						label={__('Show item 2 text', 'restatify-base')}
						checked={showItem2Text !== false}
						onChange={(v) => setAttributes({ showItem2Text: !!v })}
					/>
					<ToggleControl
						label={__('Show item 3 title', 'restatify-base')}
						checked={showItem3Title !== false}
						onChange={(v) => setAttributes({ showItem3Title: !!v })}
					/>
					<ToggleControl
						label={__('Show item 3 text', 'restatify-base')}
						checked={showItem3Text !== false}
						onChange={(v) => setAttributes({ showItem3Text: !!v })}
					/>
					<ToggleControl
						label={__('Show button label', 'restatify-base')}
						checked={showCtaLabel !== false}
						onChange={(v) => setAttributes({ showCtaLabel: !!v })}
					/>
				</PanelBody>
			</InspectorControls>

			<div className={blockClassName} style={blockStyle}>
				{backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-services-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
				<div className="container">
					<div className="row justify-content-center">
						<div className="col-12 col-lg-11">
							<div className="content-wrapper">
								<div className="row content-wrap flex-row-reverse">
									<div className="col-12 col-lg-6 card">
										<div className="title-wrapper">
											{showLabel !== false && (
												<div className="label-wrapper">
													<PlainText
														tagName="p"
														className="mbr-label mbr-fonts-style display-4"
														value={label || ''}
														onChange={(v) => setAttributes({ label: v })}
														placeholder={__('PROFESSIONAL SERVICES', 'restatify-base')}
													/>
												</div>
											)}
											{showHeading !== false && (
												<div className="title-wrap">
													<h2 className="mbr-section-title mbr-fonts-style display-2"><strong><PlainText tagName="span" value={heading || ''} onChange={(v) => setAttributes({ heading: v })} placeholder={__('Add heading', 'restatify-base')} /></strong></h2>
												</div>
											)}
										</div>
										<div className="items-wrapper">
											<div className="item features-without-image">
												<div className="item-wrapper middle-radius">
													{showItem1Title !== false && <h4 className="item-title mbr-fonts-style display-5"><strong>{item1Title || __('Planning', 'restatify-base')}</strong></h4>}
													{showItem1Text !== false && <p className="item-text mbr-fonts-style display-4">{item1Text || ''}</p>}
												</div>
											</div>
											<div className="item features-without-image">
												<div className="item-wrapper middle-radius">
													{showItem2Title !== false && <h4 className="item-title mbr-fonts-style display-5"><strong>{item2Title || __('Optimization', 'restatify-base')}</strong></h4>}
													{showItem2Text !== false && <p className="item-text mbr-fonts-style display-4">{item2Text || ''}</p>}
												</div>
											</div>
											<div className="item features-without-image">
												<div className="item-wrapper middle-radius">
													{showItem3Title !== false && <h4 className="item-title mbr-fonts-style display-5"><strong>{item3Title || __('Management', 'restatify-base')}</strong></h4>}
													{showItem3Text !== false && <p className="item-text mbr-fonts-style display-4">{item3Text || ''}</p>}
												</div>
											</div>
										</div>
									</div>
									<div className="col-12 col-lg-6 card">
										<div className="image-wrapper card-wrap">
											<div className="image-wrap">
												{imageUrl ? <img className="middle-radius" src={imageUrl} alt="" /> : <div className="middle-radius" style={{ width: '100%', height: '100%', background: 'rgba(11,18,33,0.14)' }} aria-label={imageFallbackText}></div>}
											</div>
											{showCtaLabel !== false && ctaLabel && (
												<div className="mbr-section-btn main-btn">
													<a href={ctaUrl || '#'} className="btn btn-white display-7" style={ctaColorVariables}>{ctaLabel}</a>
												</div>
											)}
										</div>
									</div>
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
		backgroundImageEnabled,
		backgroundImageUrl,
		overlayEnabled,
		overlayColor,
		overlayOpacity,
		parallax,
		fullscreen,
		label,
		showLabel,
		heading,
		showHeading,
		item1Title,
		showItem1Title,
		item1Text,
		showItem1Text,
		item2Title,
		showItem2Title,
		item2Text,
		showItem2Text,
		item3Title,
		showItem3Title,
		item3Text,
		showItem3Text,
		imageUrl,
		ctaLabel,
		showCtaLabel,
		ctaUrl,
		ctaLightNormalColor,
		ctaLightHighlightColor,
		ctaDarkNormalColor,
		ctaDarkHighlightColor
	} = attributes;

	const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
	const ctaColorVariables = getCtaColorVariables(attributes);
	const blockProps = useBlockProps.save({
		className: [
			'restatify-services-block',
			backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
			fullscreen ? 'is-fullscreen' : ''
		].join(' '),
		style: getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl)
	});

	return (
		<div {...blockProps}>
			{backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-services-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
			<div className="container">
				<div className="row justify-content-center">
					<div className="col-12 col-lg-11">
						<div className="content-wrapper">
							<div className="row content-wrap flex-row-reverse">
								<div className="col-12 col-lg-6 card">
									<div className="title-wrapper">
										{showLabel !== false && (
											<div className="label-wrapper">
												<p className="mbr-label mbr-fonts-style display-4">{label}</p>
											</div>
										)}
										{showHeading !== false && (
											<div className="title-wrap">
												<h2 className="mbr-section-title mbr-fonts-style display-2"><strong>{heading}</strong></h2>
											</div>
										)}
									</div>
									<div className="items-wrapper">
										<div className="item features-without-image">
											<div className="item-wrapper middle-radius">
												{showItem1Title !== false && <h4 className="item-title mbr-fonts-style display-5"><strong>{item1Title}</strong></h4>}
												{showItem1Text !== false && <p className="item-text mbr-fonts-style display-4">{item1Text}</p>}
											</div>
										</div>
										<div className="item features-without-image">
											<div className="item-wrapper middle-radius">
												{showItem2Title !== false && <h4 className="item-title mbr-fonts-style display-5"><strong>{item2Title}</strong></h4>}
												{showItem2Text !== false && <p className="item-text mbr-fonts-style display-4">{item2Text}</p>}
											</div>
										</div>
										<div className="item features-without-image">
											<div className="item-wrapper middle-radius">
												{showItem3Title !== false && <h4 className="item-title mbr-fonts-style display-5"><strong>{item3Title}</strong></h4>}
												{showItem3Text !== false && <p className="item-text mbr-fonts-style display-4">{item3Text}</p>}
											</div>
										</div>
									</div>
								</div>
								<div className="col-12 col-lg-6 card">
									<div className="image-wrapper card-wrap">
										<div className="image-wrap">
											{imageUrl && <img className="middle-radius" src={imageUrl} alt="" />}
										</div>
										{showCtaLabel !== false && ctaLabel && (
											<div className="mbr-section-btn main-btn">
												<a href={ctaUrl || '#'} className="btn btn-white display-7" style={ctaColorVariables}>{ctaLabel}</a>
											</div>
										)}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}

registerBlockType(metadata.name, {
	...metadata,
	edit: Edit,
	save: Save
});

