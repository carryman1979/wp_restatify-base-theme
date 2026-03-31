import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, ToggleControl, Button } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { BackgroundLayoutPanel, getBackgroundStyle, getOverlayStyle } from '../shared/background-layout-controls';
import './editor.css';
import './style.css';

function PersonImageControl({ label, value, onChange, onClear }) {
  return (
    <>
      <p>{label}</p>
      <MediaUploadCheck>
        <MediaUpload
          onSelect={(media) => onChange(media?.url || '')}
          allowedTypes={['image']}
          value={value}
          render={({ open }) => (
            <Button variant="secondary" onClick={open}>{value ? __('Replace image', 'restatify-base') : __('Choose image', 'restatify-base')}</Button>
          )}
        />
      </MediaUploadCheck>
      {value && <Button variant="link" onClick={onClear}>{__('Remove image', 'restatify-base')}</Button>}
    </>
  );
}

function TestimonialCard({ quote, showQuote, name, showName, role, showRole, imageUrl }) {
  return (
    <div className="item">
      {showQuote !== false && <p className="item-text mbr-fonts-style display-2"><strong>"{quote}"</strong></p>}
      <div className="person-wrap">
        <div className="item-img">{imageUrl ? <img src={imageUrl} alt="" /> : <div className="image-placeholder" aria-hidden="true"></div>}</div>
        <div>
          {showName !== false && <h4 className="item-name mbr-fonts-style display-7"><strong>{name}</strong></h4>}
          {showRole !== false && <p className="item-role mbr-fonts-style display-4">{role}</p>}
        </div>
      </div>
    </div>
  );
}

function Edit({ attributes, setAttributes }) {
  const {
    backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen,
    label, showLabel,
    quote1, showQuote1, person1Name, showPerson1Name, person1Role, showPerson1Role, person1ImageUrl,
    quote2, showQuote2, person2Name, showPerson2Name, person2Role, showPerson2Role, person2ImageUrl,
    quote3, showQuote3, person3Name, showPerson3Name, person3Role, showPerson3Role, person3ImageUrl
  } = attributes;

  const className = ['restatify-testimonials-block', backgroundImageEnabled !== false && parallax ? 'is-parallax' : '', fullscreen ? 'is-fullscreen' : ''].join(' ');
  const blockStyle = getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl);
  const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);

  return (
    <div {...useBlockProps()}>
      <InspectorControls>
        <BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes} />

        <PanelBody title={__('Content', 'restatify-base')} initialOpen={false}>
          <TextControl label={__('Label', 'restatify-base')} value={label} onChange={(v) => setAttributes({ label: v })} />
        </PanelBody>

        <PanelBody title={__('Testimonial 1', 'restatify-base')} initialOpen={false}>
          <TextareaControl label={__('Quote', 'restatify-base')} value={quote1} onChange={(v) => setAttributes({ quote1: v })} />
          <TextControl label={__('Name', 'restatify-base')} value={person1Name} onChange={(v) => setAttributes({ person1Name: v })} />
          <TextControl label={__('Role', 'restatify-base')} value={person1Role} onChange={(v) => setAttributes({ person1Role: v })} />
          <PersonImageControl label={__('Person image', 'restatify-base')} value={person1ImageUrl} onChange={(nextUrl) => setAttributes({ person1ImageUrl: nextUrl })} onClear={() => setAttributes({ person1ImageUrl: '' })} />
        </PanelBody>

        <PanelBody title={__('Testimonial 2', 'restatify-base')} initialOpen={false}>
          <TextareaControl label={__('Quote', 'restatify-base')} value={quote2} onChange={(v) => setAttributes({ quote2: v })} />
          <TextControl label={__('Name', 'restatify-base')} value={person2Name} onChange={(v) => setAttributes({ person2Name: v })} />
          <TextControl label={__('Role', 'restatify-base')} value={person2Role} onChange={(v) => setAttributes({ person2Role: v })} />
          <PersonImageControl label={__('Person image', 'restatify-base')} value={person2ImageUrl} onChange={(nextUrl) => setAttributes({ person2ImageUrl: nextUrl })} onClear={() => setAttributes({ person2ImageUrl: '' })} />
        </PanelBody>

        <PanelBody title={__('Testimonial 3', 'restatify-base')} initialOpen={false}>
          <TextareaControl label={__('Quote', 'restatify-base')} value={quote3} onChange={(v) => setAttributes({ quote3: v })} />
          <TextControl label={__('Name', 'restatify-base')} value={person3Name} onChange={(v) => setAttributes({ person3Name: v })} />
          <TextControl label={__('Role', 'restatify-base')} value={person3Role} onChange={(v) => setAttributes({ person3Role: v })} />
          <PersonImageControl label={__('Person image', 'restatify-base')} value={person3ImageUrl} onChange={(nextUrl) => setAttributes({ person3ImageUrl: nextUrl })} onClear={() => setAttributes({ person3ImageUrl: '' })} />
        </PanelBody>

        <PanelBody title={__('Content visibility', 'restatify-base')} initialOpen={false}>
          <ToggleControl label={__('Show label', 'restatify-base')} checked={showLabel !== false} onChange={(v) => setAttributes({ showLabel: !!v })} />
          <ToggleControl label={__('Show quote 1', 'restatify-base')} checked={showQuote1 !== false} onChange={(v) => setAttributes({ showQuote1: !!v })} />
          <ToggleControl label={__('Show person 1 name', 'restatify-base')} checked={showPerson1Name !== false} onChange={(v) => setAttributes({ showPerson1Name: !!v })} />
          <ToggleControl label={__('Show person 1 role', 'restatify-base')} checked={showPerson1Role !== false} onChange={(v) => setAttributes({ showPerson1Role: !!v })} />
          <ToggleControl label={__('Show quote 2', 'restatify-base')} checked={showQuote2 !== false} onChange={(v) => setAttributes({ showQuote2: !!v })} />
          <ToggleControl label={__('Show person 2 name', 'restatify-base')} checked={showPerson2Name !== false} onChange={(v) => setAttributes({ showPerson2Name: !!v })} />
          <ToggleControl label={__('Show person 2 role', 'restatify-base')} checked={showPerson2Role !== false} onChange={(v) => setAttributes({ showPerson2Role: !!v })} />
          <ToggleControl label={__('Show quote 3', 'restatify-base')} checked={showQuote3 !== false} onChange={(v) => setAttributes({ showQuote3: !!v })} />
          <ToggleControl label={__('Show person 3 name', 'restatify-base')} checked={showPerson3Name !== false} onChange={(v) => setAttributes({ showPerson3Name: !!v })} />
          <ToggleControl label={__('Show person 3 role', 'restatify-base')} checked={showPerson3Role !== false} onChange={(v) => setAttributes({ showPerson3Role: !!v })} />
        </PanelBody>
      </InspectorControls>

      <div className={className} style={blockStyle}>
        {backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-testimonials-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
        <div className="container"><div className="row justify-content-center"><div className="col-12 col-lg-11">
          <div className="card-wrapper">
            {showLabel !== false && <p className="mbr-label mbr-fonts-style display-4">{label}</p>}
            <div className="items">
              <TestimonialCard quote={quote1} showQuote={showQuote1} name={person1Name} showName={showPerson1Name} role={person1Role} showRole={showPerson1Role} imageUrl={person1ImageUrl} />
              <TestimonialCard quote={quote2} showQuote={showQuote2} name={person2Name} showName={showPerson2Name} role={person2Role} showRole={showPerson2Role} imageUrl={person2ImageUrl} />
              <TestimonialCard quote={quote3} showQuote={showQuote3} name={person3Name} showName={showPerson3Name} role={person3Role} showRole={showPerson3Role} imageUrl={person3ImageUrl} />
            </div>
            <div className="line-wrapper"></div>
          </div>
        </div></div></div>
      </div>
    </div>
  );
}

function Save({ attributes }) {
  const {
    backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen,
    label, showLabel,
    quote1, showQuote1, person1Name, showPerson1Name, person1Role, showPerson1Role, person1ImageUrl,
    quote2, showQuote2, person2Name, showPerson2Name, person2Role, showPerson2Role, person2ImageUrl,
    quote3, showQuote3, person3Name, showPerson3Name, person3Role, showPerson3Role, person3ImageUrl
  } = attributes;

  const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
  const blockProps = useBlockProps.save({
    className: ['restatify-testimonials-block', backgroundImageEnabled !== false && parallax ? 'is-parallax' : '', fullscreen ? 'is-fullscreen' : ''].join(' '),
    style: getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl)
  });

  return (
    <div {...blockProps}>
      {backgroundImageEnabled !== false && overlayEnabled && <div className="restatify-testimonials-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}
      <div className="container"><div className="row justify-content-center"><div className="col-12 col-lg-11">
        <div className="card-wrapper">
          {showLabel !== false && <p className="mbr-label mbr-fonts-style display-4">{label}</p>}
          <div className="items">
            <TestimonialCard quote={quote1} showQuote={showQuote1} name={person1Name} showName={showPerson1Name} role={person1Role} showRole={showPerson1Role} imageUrl={person1ImageUrl} />
            <TestimonialCard quote={quote2} showQuote={showQuote2} name={person2Name} showName={showPerson2Name} role={person2Role} showRole={showPerson2Role} imageUrl={person2ImageUrl} />
            <TestimonialCard quote={quote3} showQuote={showQuote3} name={person3Name} showName={showPerson3Name} role={person3Role} showRole={showPerson3Role} imageUrl={person3ImageUrl} />
          </div>
          <div className="line-wrapper"></div>
        </div>
      </div></div></div>
    </div>
  );
}

registerBlockType(metadata.name, { ...metadata, edit: Edit, save: Save });
