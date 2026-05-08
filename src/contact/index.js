import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck, URLInputButton } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, SelectControl, Button } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import { useEffect } from '@wordpress/element';
import metadata from './block.json';
import { BackgroundLayoutPanel, getBackgroundStyle, getOverlayStyle } from '../shared/background-layout-controls';
import './editor.css';
import './style.css';

const LEGACY_DEFAULTS = {
  phoneLabel: 'Phone',
  phoneValue: '+1 234 567 8901',
  emailLabel: 'Email',
  emailValue: 'mobirise@email.com',
  locationLabel: 'Location',
  locationValue: '125 Business Avenue, NY 10001, USA'
};

const DEFAULT_DETAILS = [
  { title: LEGACY_DEFAULTS.phoneLabel, text: LEGACY_DEFAULTS.phoneValue, mode: 'text', url: '' },
  { title: LEGACY_DEFAULTS.emailLabel, text: LEGACY_DEFAULTS.emailValue, mode: 'text', url: '' },
  { title: LEGACY_DEFAULTS.locationLabel, text: LEGACY_DEFAULTS.locationValue, mode: 'text', url: '' }
];

const DETAIL_MODE_OPTIONS = [
  { label: __('Text only (label + text)', 'restatify-base'), value: 'text' },
  { label: __('Text with hyperlink', 'restatify-base'), value: 'link' },
  { label: __('Button (label as button text)', 'restatify-base'), value: 'button' }
];

function normalizeDetail(detail = {}) {
  return {
    title: detail.title || '',
    text: detail.text || '',
    mode: ['text', 'link', 'button'].includes(detail.mode) ? detail.mode : 'text',
    url: detail.url || ''
  };
}

function normalizeDetails(details) {
  const source = Array.isArray(details) ? details : [];
  const normalized = source.map(normalizeDetail);

  if (normalized.length > 0) {
    return normalized;
  }

  return DEFAULT_DETAILS.map((detail) => ({ ...detail }));
}

function detailsFromLegacy(attributes) {
  return [
    {
      title: attributes.phoneLabel || LEGACY_DEFAULTS.phoneLabel,
      text: attributes.phoneValue || '',
      mode: 'text',
      url: ''
    },
    {
      title: attributes.emailLabel || LEGACY_DEFAULTS.emailLabel,
      text: attributes.emailValue || '',
      mode: 'text',
      url: ''
    },
    {
      title: attributes.locationLabel || LEGACY_DEFAULTS.locationLabel,
      text: attributes.locationValue || '',
      mode: 'text',
      url: ''
    }
  ];
}

function isDefaultDetails(details) {
  const normalized = normalizeDetails(details);

  if (normalized.length !== DEFAULT_DETAILS.length) {
    return false;
  }

  return normalized.every((detail, index) => {
    const fallback = DEFAULT_DETAILS[index];
    return detail.title === fallback.title && detail.text === fallback.text && detail.mode === fallback.mode && detail.url === fallback.url;
  });
}

function hasLegacyCustomValues(attributes) {
  return attributes.phoneLabel !== LEGACY_DEFAULTS.phoneLabel || attributes.phoneValue !== LEGACY_DEFAULTS.phoneValue || attributes.emailLabel !== LEGACY_DEFAULTS.emailLabel || attributes.emailValue !== LEGACY_DEFAULTS.emailValue || attributes.locationLabel !== LEGACY_DEFAULTS.locationLabel || attributes.locationValue !== LEGACY_DEFAULTS.locationValue;
}

function updateDetail(details, index, patch) {
  return details.map((detail, currentIndex) => (currentIndex === index ? { ...detail, ...patch } : detail));
}

function moveDetail(details, from, to) {
  if (to < 0 || to >= details.length) {
    return details;
  }

  const next = [...details];
  const [moved] = next.splice(from, 1);
  next.splice(to, 0, moved);
  return next;
}

function addDetail(details) {
  return [...details, { title: '', text: '', mode: 'text', url: '' }];
}

function removeDetail(details, index) {
  return details.filter((_, currentIndex) => currentIndex !== index);
}

function ContactItem({ detail }) {
  const safeDetail = normalizeDetail(detail);
  const isButton = safeDetail.mode === 'button' && safeDetail.url;
  const isLinkedText = safeDetail.mode === 'link' && safeDetail.url;

  return (
    <div className="item">
      <div className="item-wrapper">
        {isButton ? (
          <a className="item-link-button btn btn-sm btn-primary display-7" href={safeDetail.url}>
            {safeDetail.title || __('Open link', 'restatify-base')}
          </a>
        ) : (
          <>
            <h4 className="item-title mbr-fonts-style display-4">{safeDetail.title}</h4>
            {isLinkedText ? (
              <p className="item-text mbr-fonts-style display-7">
                <a className="item-text-link" href={safeDetail.url}><strong>{safeDetail.text}</strong></a>
              </p>
            ) : (
              <p className="item-text mbr-fonts-style display-7"><strong>{safeDetail.text}</strong></p>
            )}
          </>
        )}
      </div>
    </div>
  );
}

function Edit({ attributes, setAttributes }) {
  const {
    backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen,
    label, heading, text, details, phoneLabel, phoneValue, emailLabel, emailValue, locationLabel, locationValue, imageUrl
  } = attributes;

  const normalizedDetails = normalizeDetails(details);
  const className=['restatify-contact-block', backgroundImageEnabled!==false&&parallax?'is-parallax':'', fullscreen?'is-fullscreen':''].join(' ');
  const style=getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl);
  const overlayStyle=getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);

  useEffect(() => {
    if (hasLegacyCustomValues(attributes) && isDefaultDetails(normalizedDetails)) {
      setAttributes({ details: detailsFromLegacy(attributes) });
    }
  }, [attributes, normalizedDetails, setAttributes]);

  return <div {...useBlockProps()}><InspectorControls><BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes}/><PanelBody title={__('Content','restatify-base')} initialOpen={false}><TextControl label={__('Label','restatify-base')} value={label} onChange={(v)=>setAttributes({label:v})}/><TextControl label={__('Heading','restatify-base')} value={heading} onChange={(v)=>setAttributes({heading:v})}/><TextareaControl label={__('Text','restatify-base')} value={text} onChange={(v)=>setAttributes({text:v})}/></PanelBody><PanelBody title={__('Details','restatify-base')} initialOpen={false}>{normalizedDetails.map((detail,index)=><div className="restatify-detail-editor" key={`detail-${index}`}><TextControl label={__('Label','restatify-base')} value={detail.title} onChange={(v)=>setAttributes({details:updateDetail(normalizedDetails,index,{title:v})})}/><SelectControl label={__('Display mode','restatify-base')} value={detail.mode} options={DETAIL_MODE_OPTIONS} onChange={(v)=>setAttributes({details:updateDetail(normalizedDetails,index,{mode:v})})}/>{detail.mode!=='button'&&<TextControl label={__('Text','restatify-base')} value={detail.text} onChange={(v)=>setAttributes({details:updateDetail(normalizedDetails,index,{text:v})})}/>} {detail.mode!=='text'&&<><p className="restatify-detail-link-label">{__('Link','restatify-base')}</p><URLInputButton url={detail.url} onChange={(url)=>setAttributes({details:updateDetail(normalizedDetails,index,{url:url||''})})}/></>}<div className="restatify-detail-order-controls"><Button variant="secondary" disabled={index===0} onClick={()=>setAttributes({details:moveDetail(normalizedDetails,index,index-1)})}>{__('Move up','restatify-base')}</Button><Button variant="secondary" disabled={index===normalizedDetails.length-1} onClick={()=>setAttributes({details:moveDetail(normalizedDetails,index,index+1)})}>{__('Move down','restatify-base')}</Button><Button variant="secondary" onClick={()=>setAttributes({details:removeDetail(normalizedDetails,index)})}>{__('Remove','restatify-base')}</Button></div></div>)}<Button variant="primary" onClick={()=>setAttributes({details:addDetail(normalizedDetails)})}>{__('Add detail','restatify-base')}</Button></PanelBody><PanelBody title={__('Image','restatify-base')} initialOpen={false}><MediaUploadCheck><MediaUpload onSelect={(m)=>setAttributes({imageUrl:m?.url||''})} allowedTypes={['image']} value={imageUrl} render={({open})=><Button variant="secondary" onClick={open}>{imageUrl?__('Replace image','restatify-base'):__('Choose image','restatify-base')}</Button>}/></MediaUploadCheck>{imageUrl&&<Button variant="link" onClick={()=>setAttributes({imageUrl:''})}>{__('Remove image','restatify-base')}</Button>}</PanelBody></InspectorControls><div className={className} style={style}>{backgroundImageEnabled!==false&&overlayEnabled&&<div className="restatify-contact-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}<div className="container"><div className="row justify-content-center"><div className="col-12 col-lg-11"><div className="content-wrapper"><p className="mbr-label mbr-fonts-style display-4">{label}</p><h2 className="mbr-section-title mbr-fonts-style display-2"><strong>{heading}</strong></h2>{text&&<p className="mbr-text mbr-fonts-style display-4" style={{whiteSpace:'pre-line'}}>{text}</p>}<div className="row content-wrap"><div className="col-12 col-lg-6 card"><div className="items-wrapper">{normalizedDetails.map((detail,index)=><ContactItem key={`contact-item-${index}`} detail={detail}/>)}</div></div><div className="col-12 col-lg-6 card"><div className="image-wrapper">{imageUrl?<img src={imageUrl} alt="" loading="lazy"/>:<div className="image-placeholder" aria-label={__('Choose image','restatify-base')}></div>}</div></div></div></div></div></div></div></div></div>;
}

function Save({ attributes }) {
  const { backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen, label, heading, text, details, imageUrl } = attributes;
  const normalizedDetails = normalizeDetails(details);
  const overlayStyle=getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity); const blockProps=useBlockProps.save({className:['restatify-contact-block', backgroundImageEnabled!==false&&parallax?'is-parallax':'', fullscreen?'is-fullscreen':''].join(' '), style:getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl)});
  return <div {...blockProps}>{backgroundImageEnabled!==false&&overlayEnabled&&<div className="restatify-contact-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}<div className="container"><div className="row justify-content-center"><div className="col-12 col-lg-11"><div className="content-wrapper"><p className="mbr-label mbr-fonts-style display-4">{label}</p><h2 className="mbr-section-title mbr-fonts-style display-2"><strong>{heading}</strong></h2>{text&&<p className="mbr-text mbr-fonts-style display-4" style={{whiteSpace:'pre-line'}}>{text}</p>}<div className="row content-wrap"><div className="col-12 col-lg-6 card"><div className="items-wrapper">{normalizedDetails.map((detail,index)=><ContactItem key={`contact-item-${index}`} detail={detail}/>)}</div></div><div className="col-12 col-lg-6 card"><div className="image-wrapper">{imageUrl&&<img src={imageUrl} alt="" loading="lazy"/>}</div></div></div></div></div></div></div></div>;
}

registerBlockType(metadata.name,{...metadata,edit:Edit,save:Save});
