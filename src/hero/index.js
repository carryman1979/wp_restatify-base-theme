import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, PlainText, __experimentalLinkControl as LinkControl } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
	Button,
	ToggleControl
} from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { BackgroundLayoutPanel, getBackgroundStyle, getOverlayStyle } from '../shared/background-layout-controls';
import { getUrlFromLinkValue } from '../shared/link-utils';
import './editor.css';
import './style.css';

function Edit({ attributes, setAttributes }) {
	const {
		backgroundImageEnabled,
		backgroundImageUrl,
		overlayEnabled,
		overlayColor,
		overlayOpacity,
		parallax,
		fullscreen,
		tagline,
		showTagline,
		heading,
		showHeading,
		text,
		showText,
		btn1Label,
		showBtn1,
		btn1Url,
		btn2Label,
		showBtn2,
		btn2Url
	} = attributes;
	const heroStyle = getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl);
	const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
	const heroClassName = [
		'restatify-hero-block',
		backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
		fullscreen ? 'is-fullscreen' : ''
	].join(' ');
	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes} />

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

				<PanelBody title={__('Content visibility', 'restatify-base')} initialOpen={false}>
					<ToggleControl
						label={__('Show tagline', 'restatify-base')}
						checked={showTagline !== false}
						onChange={(v) => setAttributes({ showTagline: !!v })}
					/>
					<ToggleControl
						label={__('Show heading', 'restatify-base')}
						checked={showHeading !== false}
						onChange={(v) => setAttributes({ showHeading: !!v })}
					/>
					<ToggleControl
						label={__('Show text', 'restatify-base')}
						checked={showText !== false}
						onChange={(v) => setAttributes({ showText: !!v })}
					/>
					<ToggleControl
						label={__('Show button 1', 'restatify-base')}
						checked={showBtn1 !== false}
						onChange={(v) => setAttributes({ showBtn1: !!v })}
					/>
					<ToggleControl
						label={__('Show button 2', 'restatify-base')}
						checked={showBtn2 !== false}
						onChange={(v) => setAttributes({ showBtn2: !!v })}
					/>
				</PanelBody>
			</InspectorControls>
			<div className={ heroClassName } style={ heroStyle }>
				{backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-hero-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
				<div className="container">
					<div className="row justify-content-center">
						<div className="col-12 col-lg-11">
							<div className="content-wrapper card-wrap">
								<div className="border-wrap card-wrap"></div>
								<div className="shadow-wrap"></div>
								<div className="content-wrap card-wrap middle-radius">
									<div className="title-wrapper">
										<div className="title-wrap">
											{showTagline !== false && (
												<div className="desc-wrapper">
													<PlainText
														tagName="p"
														className="mbr-desc card-wrap mbr-fonts-style display-4"
														value={tagline || ''}
														onChange={(v) => setAttributes({ tagline: v })}
														placeholder={__('Add your tagline', 'restatify-base')}
													/>
												</div>
											)}
											{showHeading !== false && <h2 className="mbr-section-title mbr-fonts-style display-1"><strong><PlainText tagName="span" value={heading || ''} onChange={(v) => setAttributes({ heading: v })} placeholder={__('Hero heading', 'restatify-base')} /></strong></h2>}
											{showText !== false && (
												<div className="text-wrapper">
													<PlainText
														tagName="p"
														className="mbr-text mbr-fonts-style display-7"
														value={text || ''}
														onChange={(v) => setAttributes({ text: v })}
														placeholder={__('Add a short supporting text for your offer.', 'restatify-base')}
													/>
												</div>
											)}
											<div className="frame-wrapper card-bg middle-radius frame_1"><div className="frame-wrap"></div></div>
											<div className="frame-wrapper card-bg middle-radius frame_2"><div className="frame-wrap"></div></div>
										</div>
									</div>
									<div className="card-box">
										<div className="mbr-section-btn">
											{showBtn1 !== false && <a className="btn btn-black display-7" href={btn1Url || '#'}>{btn1Label || __('Primary action', 'restatify-base')}</a>}
											{showBtn2 !== false && (
												<a className="btn btn-black-outline display-7" href={btn2Url || '#'}>
													<span className="mobi-mbri mobi-mbri-right mbr-iconfont mbr-iconfont-btn"></span>
													{btn2Label || __('Secondary action', 'restatify-base')}
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
		tagline,
		showTagline,
		heading,
		showHeading,
		text,
		showText,
		btn1Label,
		showBtn1,
		btn1Url,
		btn2Label,
		showBtn2,
		btn2Url
	} = attributes;
	const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
	const blockProps = useBlockProps.save({
		className: [
			'restatify-hero-block',
			backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
			fullscreen ? 'is-fullscreen' : ''
		].join(' '),
		style: getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl)
	});

	return (
		<div {...blockProps}>
			{backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-hero-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
			<div className="container">
				<div className="row justify-content-center">
					<div className="col-12 col-lg-11">
						<div className="content-wrapper card-wrap">
							<div className="border-wrap card-wrap"></div>
							<div className="shadow-wrap"></div>
							<div className="content-wrap card-wrap middle-radius">
								<div className="title-wrapper">
									<div className="title-wrap">
										{showTagline !== false && (
											<div className="desc-wrapper">
												<p className="mbr-desc card-wrap mbr-fonts-style display-4">{tagline}</p>
											</div>
										)}
										{showHeading !== false && <h2 className="mbr-section-title mbr-fonts-style display-1"><strong>{heading}</strong></h2>}
										{showText !== false && (
											<div className="text-wrapper">
												<p className="mbr-text mbr-fonts-style display-7">{text}</p>
											</div>
										)}
										<div className="frame-wrapper card-bg middle-radius frame_1"><div className="frame-wrap"></div></div>
										<div className="frame-wrapper card-bg middle-radius frame_2"><div className="frame-wrap"></div></div>
									</div>
								</div>
								<div className="card-box">
									<div className="mbr-section-btn">
										{showBtn1 !== false && btn1Label && <a className="btn btn-black display-7" href={btn1Url || '#'}>{btn1Label}</a>}
										{showBtn2 !== false && btn2Label && (
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
