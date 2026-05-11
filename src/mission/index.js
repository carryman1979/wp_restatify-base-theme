import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck, PlainText } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, ToggleControl, Button } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { BackgroundLayoutPanel, getBackgroundStyle, getOverlayStyle } from '../shared/background-layout-controls';
import './editor.css';
import './style.css';

function Edit({ attributes, setAttributes }) {
  const {
    backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen,
    heading, showHeading, subheading, showSubheading, text, showText, imageUrl, showImage
  } = attributes;

  const className = ['restatify-mission-block', backgroundImageEnabled !== false && parallax ? 'is-parallax' : '', fullscreen ? 'is-fullscreen' : ''].join(' ');
  const blockStyle = getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl);
  const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);

  return (
    <div {...useBlockProps()}>
      <InspectorControls>
        <BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes} />
        <PanelBody title={__('Content', 'restatify-base')} initialOpen={false}>
          <TextControl label={__('Heading', 'restatify-base')} value={heading} onChange={(v) => setAttributes({ heading: v })} />
          <TextControl label={__('Subheading', 'restatify-base')} value={subheading} onChange={(v) => setAttributes({ subheading: v })} />
          <TextareaControl label={__('Text', 'restatify-base')} value={text} onChange={(v) => setAttributes({ text: v })} />
        </PanelBody>
        <PanelBody title={__('Image', 'restatify-base')} initialOpen={false}>
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ imageUrl: media?.url || '' })}
              allowedTypes={['image']}
              value={imageUrl}
              render={({ open }) => <Button variant="secondary" onClick={open}>{imageUrl ? __('Replace image', 'restatify-base') : __('Choose image', 'restatify-base')}</Button>}
            />
          </MediaUploadCheck>
          {imageUrl && <Button variant="link" onClick={() => setAttributes({ imageUrl: '' })}>{__('Remove image', 'restatify-base')}</Button>}
        </PanelBody>
        <PanelBody title={__('Content visibility', 'restatify-base')} initialOpen={false}>
          <ToggleControl label={__('Show heading', 'restatify-base')} checked={showHeading !== false} onChange={(v) => setAttributes({ showHeading: !!v })} />
          <ToggleControl label={__('Show subheading', 'restatify-base')} checked={showSubheading !== false} onChange={(v) => setAttributes({ showSubheading: !!v })} />
          <ToggleControl label={__('Show text', 'restatify-base')} checked={showText !== false} onChange={(v) => setAttributes({ showText: !!v })} />
          <ToggleControl label={__('Show image', 'restatify-base')} checked={showImage !== false} onChange={(v) => setAttributes({ showImage: !!v })} />
        </PanelBody>
      </InspectorControls>

      <div className={className} style={blockStyle}>
        {backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-mission-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
        <div className="container"><div className="row justify-content-center"><div className="col-12 col-lg-8">
          <div className="content-wrapper card-wrap">
            {showHeading !== false && <h2 className="mbr-section-title mbr-fonts-style display-1"><strong><PlainText tagName="span" value={heading || ''} onChange={(v) => setAttributes({ heading: v })} placeholder={__('Heading', 'restatify-base')} /></strong></h2>}
            {showSubheading !== false && <h3 className="mbr-section-subtitle mbr-fonts-style display-4"><strong><PlainText tagName="span" value={subheading || ''} onChange={(v) => setAttributes({ subheading: v })} placeholder={__('Subheading', 'restatify-base')} /></strong></h3>}
            {showText !== false && <PlainText tagName="p" className="mbr-text mbr-fonts-style display-4" value={text || ''} onChange={(v) => setAttributes({ text: v })} placeholder={__('Text', 'restatify-base')} />}
          </div>
          {showImage !== false && (
            <div className="image-wrapper">
              {imageUrl ? <img src={imageUrl} alt="" loading="lazy" /> : <div className="image-placeholder" aria-label={__('Choose image', 'restatify-base')}></div>}
            </div>
          )}
        </div></div></div>
      </div>
    </div>
  );
}

function Save({ attributes }) {
  const {
    backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen,
    heading, showHeading, subheading, showSubheading, text, showText, imageUrl, showImage
  } = attributes;
  const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
  const blockProps = useBlockProps.save({ className: ['restatify-mission-block', backgroundImageEnabled !== false && parallax ? 'is-parallax' : '', fullscreen ? 'is-fullscreen' : ''].join(' '), style: getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl) });

  return (
    <div {...blockProps}>
      {backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-mission-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
      <div className="container"><div className="row justify-content-center"><div className="col-12 col-lg-8">
        <div className="content-wrapper card-wrap">
          {showHeading !== false && <h2 className="mbr-section-title mbr-fonts-style display-1"><strong>{heading}</strong></h2>}
          {showSubheading !== false && <h3 className="mbr-section-subtitle mbr-fonts-style display-4"><strong>{subheading}</strong></h3>}
          {showText !== false && <p className="mbr-text mbr-fonts-style display-4" style={{ whiteSpace: 'pre-line' }}>{text}</p>}
        </div>
        {showImage !== false && <div className="image-wrapper">{imageUrl && <img src={imageUrl} alt="" loading="lazy" />}</div>}
      </div></div></div>
    </div>
  );
}

registerBlockType(metadata.name, { ...metadata, edit: Edit, save: Save });
