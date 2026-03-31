import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
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

function getStudiesColorVariables(attributes) {
	const {
		cardSurfaceColor,
		cardBorderColor,
		lineStartColor,
		lineEndColor,
		linkColor,
		linkHoverColor
	} = attributes;
	const style = {};

	if (cardSurfaceColor) {
		style['--rs-studies-surface'] = cardSurfaceColor;
	}

	if (cardBorderColor) {
		style['--rs-studies-border'] = cardBorderColor;
	}

	if (lineStartColor) {
		style['--rs-studies-line-start'] = lineStartColor;
	}

	if (lineEndColor) {
		style['--rs-studies-line-end'] = lineEndColor;
	}

	if (linkColor) {
		style['--rs-studies-link'] = linkColor;
	}

	if (linkHoverColor) {
		style['--rs-studies-link-hover'] = linkHoverColor;
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
		heading,
		showHeading,
		introText,
		showIntroText,
		card1ImageUrl,
		card1Title,
		showCard1Title,
		card1Text,
		showCard1Text,
		card1ButtonLabel,
		showCard1ButtonLabel,
		card1Url,
		card2ImageUrl,
		card2Title,
		showCard2Title,
		card2Text,
		showCard2Text,
		card2ButtonLabel,
		showCard2ButtonLabel,
		card2Url,
		cardSurfaceColor,
		cardBorderColor,
		lineStartColor,
		lineEndColor,
		linkColor,
		linkHoverColor
	} = attributes;

	const blockClassName = [
		'restatify-studies-block',
		backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
		fullscreen ? 'is-fullscreen' : ''
	].join(' ');

	const blockStyle = {
		...getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl),
		...getStudiesColorVariables(attributes)
	};

	const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
	const themeColorPresets = useMemo(() => getThemeColorPresets(), []);
	const normalizedCard1Url = card1Url === '#' ? '' : card1Url;
	const normalizedCard2Url = card2Url === '#' ? '' : card2Url;

	return (
		<div {...useBlockProps()}>
			<InspectorControls>
				<BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes} />

				<PanelBody title={__('Section content', 'restatify-base')} initialOpen={false}>
					<TextareaControl
						label={__('Heading', 'restatify-base')}
						value={heading}
						onChange={(v) => setAttributes({ heading: v })}
					/>
					<TextareaControl
						label={__('Intro text', 'restatify-base')}
						value={introText}
						onChange={(v) => setAttributes({ introText: v })}
					/>
				</PanelBody>

				<PanelBody title={__('Card 1', 'restatify-base')} initialOpen={false}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => setAttributes({ card1ImageUrl: media?.url || '' })}
							allowedTypes={['image']}
							value={card1ImageUrl}
							render={({ open }) => (
								<Button variant="secondary" onClick={open}>
									{card1ImageUrl ? __('Replace card image', 'restatify-base') : __('Choose card image', 'restatify-base')}
								</Button>
							)}
						/>
					</MediaUploadCheck>
					{card1ImageUrl && (
						<Button variant="link" onClick={() => setAttributes({ card1ImageUrl: '' })}>
							{__('Remove card image', 'restatify-base')}
						</Button>
					)}
					<TextControl
						label={__('Title', 'restatify-base')}
						value={card1Title}
						onChange={(v) => setAttributes({ card1Title: v })}
					/>
					<TextareaControl
						label={__('Text', 'restatify-base')}
						value={card1Text}
						onChange={(v) => setAttributes({ card1Text: v })}
					/>
					<TextControl
						label={__('Button label', 'restatify-base')}
						value={card1ButtonLabel}
						onChange={(v) => setAttributes({ card1ButtonLabel: v })}
					/>
					<p>{__('Card link', 'restatify-base')}</p>
					<LinkControl
						value={{ url: normalizedCard1Url || '' }}
						onChange={(nextValue) => setAttributes({ card1Url: getUrlFromLinkValue(nextValue) })}
						settings={[]}
					/>
				</PanelBody>

				<PanelBody title={__('Card 2', 'restatify-base')} initialOpen={false}>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={(media) => setAttributes({ card2ImageUrl: media?.url || '' })}
							allowedTypes={['image']}
							value={card2ImageUrl}
							render={({ open }) => (
								<Button variant="secondary" onClick={open}>
									{card2ImageUrl ? __('Replace card image', 'restatify-base') : __('Choose card image', 'restatify-base')}
								</Button>
							)}
						/>
					</MediaUploadCheck>
					{card2ImageUrl && (
						<Button variant="link" onClick={() => setAttributes({ card2ImageUrl: '' })}>
							{__('Remove card image', 'restatify-base')}
						</Button>
					)}
					<TextControl
						label={__('Title', 'restatify-base')}
						value={card2Title}
						onChange={(v) => setAttributes({ card2Title: v })}
					/>
					<TextareaControl
						label={__('Text', 'restatify-base')}
						value={card2Text}
						onChange={(v) => setAttributes({ card2Text: v })}
					/>
					<TextControl
						label={__('Button label', 'restatify-base')}
						value={card2ButtonLabel}
						onChange={(v) => setAttributes({ card2ButtonLabel: v })}
					/>
					<p>{__('Card link', 'restatify-base')}</p>
					<LinkControl
						value={{ url: normalizedCard2Url || '' }}
						onChange={(nextValue) => setAttributes({ card2Url: getUrlFromLinkValue(nextValue) })}
						settings={[]}
					/>
				</PanelBody>

				<PanelBody title={__('Style overrides (optional)', 'restatify-base')} initialOpen={false}>
					<ThemeColorControl
						label={__('Card background color', 'restatify-base')}
						value={cardSurfaceColor}
						onChange={(nextColor) => setAttributes({ cardSurfaceColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<ThemeColorControl
						label={__('Card border color', 'restatify-base')}
						value={cardBorderColor}
						onChange={(nextColor) => setAttributes({ cardBorderColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<ThemeColorControl
						label={__('Bottom line start color', 'restatify-base')}
						value={lineStartColor}
						onChange={(nextColor) => setAttributes({ lineStartColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<ThemeColorControl
						label={__('Bottom line end color', 'restatify-base')}
						value={lineEndColor}
						onChange={(nextColor) => setAttributes({ lineEndColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<ThemeColorControl
						label={__('Link color', 'restatify-base')}
						value={linkColor}
						onChange={(nextColor) => setAttributes({ linkColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<ThemeColorControl
						label={__('Link hover color', 'restatify-base')}
						value={linkHoverColor}
						onChange={(nextColor) => setAttributes({ linkHoverColor: nextColor })}
						themeColorPresets={themeColorPresets}
					/>
					<Button
						variant="link"
						onClick={() => setAttributes({
							cardSurfaceColor: '',
							cardBorderColor: '',
							lineStartColor: '',
							lineEndColor: '',
							linkColor: '',
							linkHoverColor: ''
						})}
					>
						{__('Reset colors to default', 'restatify-base')}
					</Button>
				</PanelBody>

				<PanelBody title={__('Content visibility', 'restatify-base')} initialOpen={false}>
					<ToggleControl
						label={__('Show heading', 'restatify-base')}
						checked={showHeading !== false}
						onChange={(v) => setAttributes({ showHeading: !!v })}
					/>
					<ToggleControl
						label={__('Show intro text', 'restatify-base')}
						checked={showIntroText !== false}
						onChange={(v) => setAttributes({ showIntroText: !!v })}
					/>
					<ToggleControl
						label={__('Show card 1 title', 'restatify-base')}
						checked={showCard1Title !== false}
						onChange={(v) => setAttributes({ showCard1Title: !!v })}
					/>
					<ToggleControl
						label={__('Show card 1 text', 'restatify-base')}
						checked={showCard1Text !== false}
						onChange={(v) => setAttributes({ showCard1Text: !!v })}
					/>
					<ToggleControl
						label={__('Show card 1 button label', 'restatify-base')}
						checked={showCard1ButtonLabel !== false}
						onChange={(v) => setAttributes({ showCard1ButtonLabel: !!v })}
					/>
					<ToggleControl
						label={__('Show card 2 title', 'restatify-base')}
						checked={showCard2Title !== false}
						onChange={(v) => setAttributes({ showCard2Title: !!v })}
					/>
					<ToggleControl
						label={__('Show card 2 text', 'restatify-base')}
						checked={showCard2Text !== false}
						onChange={(v) => setAttributes({ showCard2Text: !!v })}
					/>
					<ToggleControl
						label={__('Show card 2 button label', 'restatify-base')}
						checked={showCard2ButtonLabel !== false}
						onChange={(v) => setAttributes({ showCard2ButtonLabel: !!v })}
					/>
				</PanelBody>
			</InspectorControls>

			<div className={blockClassName} style={blockStyle}>
				{backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-studies-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
				<div className="container">
					<div className="row justify-content-center">
						<div className="col-12 col-lg-11">
							<div className="content-wrapper">
								<div className="row justify-content-between">
									<div className="col-12 col-lg-6 card">
										<div className="title-wrapper">
											{showHeading !== false && (
												<h2 className="mbr-section-title mbr-fonts-style display-2">
													<strong>{heading}</strong>
												</h2>
											)}
										</div>
									</div>
									<div className="col-12 col-lg-5 card">
										<div className="text-wrapper">
											{showIntroText !== false && <p className="mbr-text mbr-fonts-style display-4">{introText}</p>}
										</div>
									</div>
									<div className="col-12">
										<div className="card-wrapper">
											<div className="row items-wrapper justify-content-center">
												<div className="col-12 col-lg-6 item features-image middle-radius">
													<div className="item-wrapper middle-radius">
														<div className="item-img card-wrap little-radius">
															<a href={card1Url || '#'}>
																{card1ImageUrl ? <img className="little-radius" src={card1ImageUrl} alt="" loading="lazy" /> : <div className="image-placeholder little-radius" aria-label={__('Choose image', 'restatify-base')}></div>}
															</a>
														</div>
														<div className="card-box">
															{showCard1Title !== false && (
																<a href={card1Url || '#'}>
																	<h4 className="item-title mbr-fonts-style display-5"><strong>{card1Title}</strong></h4>
																</a>
															)}
															{showCard1Text !== false && <p className="item-text mbr-fonts-style display-4">{card1Text}</p>}
															<div className="mbr-section-btn item-btn">
																{showCard1ButtonLabel !== false && <a href={card1Url || '#'} className="btn btn-black-outline display-7">
																	<span className="mobi-mbri mobi-mbri-right mbr-iconfont mbr-iconfont-btn"></span>
																	{card1ButtonLabel}
																</a>}
															</div>
														</div>
													</div>
												</div>
												<div className="col-12 col-lg-6 item features-image middle-radius">
													<div className="item-wrapper middle-radius">
														<div className="item-img card-wrap little-radius">
															<a href={card2Url || '#'}>
																{card2ImageUrl ? <img className="little-radius" src={card2ImageUrl} alt="" loading="lazy" /> : <div className="image-placeholder little-radius" aria-label={__('Choose image', 'restatify-base')}></div>}
															</a>
														</div>
														<div className="card-box">
															{showCard2Title !== false && (
																<a href={card2Url || '#'}>
																	<h4 className="item-title mbr-fonts-style display-5"><strong>{card2Title}</strong></h4>
																</a>
															)}
															{showCard2Text !== false && <p className="item-text mbr-fonts-style display-4">{card2Text}</p>}
															<div className="mbr-section-btn item-btn">
																{showCard2ButtonLabel !== false && <a href={card2Url || '#'} className="btn btn-black-outline display-7">
																	<span className="mobi-mbri mobi-mbri-right mbr-iconfont mbr-iconfont-btn"></span>
																	{card2ButtonLabel}
																</a>}
															</div>
														</div>
													</div>
												</div>
											</div>
											<div className="line-wrapper"></div>
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
		heading,
		showHeading,
		introText,
		showIntroText,
		card1ImageUrl,
		card1Title,
		showCard1Title,
		card1Text,
		showCard1Text,
		card1ButtonLabel,
		showCard1ButtonLabel,
		card1Url,
		card2ImageUrl,
		card2Title,
		showCard2Title,
		card2Text,
		showCard2Text,
		card2ButtonLabel,
		showCard2ButtonLabel,
		card2Url
	} = attributes;

	const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
	const blockProps = useBlockProps.save({
		className: [
			'restatify-studies-block',
			backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
			fullscreen ? 'is-fullscreen' : ''
		].join(' '),
		style: {
			...getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl),
			...getStudiesColorVariables(attributes)
		}
	});

	return (
		<div {...blockProps}>
			{backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-studies-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
			<div className="container">
				<div className="row justify-content-center">
					<div className="col-12 col-lg-11">
						<div className="content-wrapper">
							<div className="row justify-content-between">
								<div className="col-12 col-lg-6 card">
									<div className="title-wrapper">
										{showHeading !== false && <h2 className="mbr-section-title mbr-fonts-style display-2"><strong>{heading}</strong></h2>}
									</div>
								</div>
								<div className="col-12 col-lg-5 card">
									<div className="text-wrapper">
										{showIntroText !== false && <p className="mbr-text mbr-fonts-style display-4">{introText}</p>}
									</div>
								</div>
								<div className="col-12">
									<div className="card-wrapper">
										<div className="row items-wrapper justify-content-center">
											<div className="col-12 col-lg-6 item features-image middle-radius">
												<div className="item-wrapper middle-radius">
													<div className="item-img card-wrap little-radius">
														<a href={card1Url || '#'}>
															{card1ImageUrl && <img className="little-radius" src={card1ImageUrl} alt="" loading="lazy" />}
														</a>
													</div>
													<div className="card-box">
														{showCard1Title !== false && (
															<a href={card1Url || '#'}>
																<h4 className="item-title mbr-fonts-style display-5"><strong>{card1Title}</strong></h4>
															</a>
														)}
														{showCard1Text !== false && <p className="item-text mbr-fonts-style display-4">{card1Text}</p>}
														<div className="mbr-section-btn item-btn">
															{showCard1ButtonLabel !== false && <a href={card1Url || '#'} className="btn btn-black-outline display-7">
																<span className="mobi-mbri mobi-mbri-right mbr-iconfont mbr-iconfont-btn"></span>
																{card1ButtonLabel}
															</a>}
														</div>
													</div>
												</div>
											</div>
											<div className="col-12 col-lg-6 item features-image middle-radius">
												<div className="item-wrapper middle-radius">
													<div className="item-img card-wrap little-radius">
														<a href={card2Url || '#'}>
															{card2ImageUrl && <img className="little-radius" src={card2ImageUrl} alt="" loading="lazy" />}
														</a>
													</div>
													<div className="card-box">
														{showCard2Title !== false && (
															<a href={card2Url || '#'}>
																<h4 className="item-title mbr-fonts-style display-5"><strong>{card2Title}</strong></h4>
															</a>
														)}
														{showCard2Text !== false && <p className="item-text mbr-fonts-style display-4">{card2Text}</p>}
														<div className="mbr-section-btn item-btn">
															{showCard2ButtonLabel !== false && <a href={card2Url || '#'} className="btn btn-black-outline display-7">
																<span className="mobi-mbri mobi-mbri-right mbr-iconfont mbr-iconfont-btn"></span>
																{card2ButtonLabel}
															</a>}
														</div>
													</div>
												</div>
											</div>
										</div>
										<div className="line-wrapper"></div>
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

