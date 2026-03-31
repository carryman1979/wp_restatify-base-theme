import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, ToggleControl } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { BackgroundLayoutPanel, getBackgroundStyle, getOverlayStyle } from '../shared/background-layout-controls';
import './editor.css';
import './style.css';

function QA({ q, a }) { return <details><summary>{q}</summary><p>{a}</p></details>; }

function Edit({ attributes, setAttributes }) {
  const { backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen, heading, showHeading, q1, a1, q2, a2, q3, a3, q4, a4 } = attributes;
  const className=['restatify-faq-block', backgroundImageEnabled!==false&&parallax?'is-parallax':'', fullscreen?'is-fullscreen':''].join(' ');
  const style=getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl);
  const overlayStyle=getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
  return <div {...useBlockProps()}><InspectorControls><BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes}/><PanelBody title={__('Content','restatify-base')} initialOpen={false}><TextControl label={__('Heading','restatify-base')} value={heading} onChange={(v)=>setAttributes({heading:v})}/></PanelBody><PanelBody title={__('Questions','restatify-base')} initialOpen={false}><TextControl label={__('Question 1','restatify-base')} value={q1} onChange={(v)=>setAttributes({q1:v})}/><TextareaControl label={__('Answer 1','restatify-base')} value={a1} onChange={(v)=>setAttributes({a1:v})}/><TextControl label={__('Question 2','restatify-base')} value={q2} onChange={(v)=>setAttributes({q2:v})}/><TextareaControl label={__('Answer 2','restatify-base')} value={a2} onChange={(v)=>setAttributes({a2:v})}/><TextControl label={__('Question 3','restatify-base')} value={q3} onChange={(v)=>setAttributes({q3:v})}/><TextareaControl label={__('Answer 3','restatify-base')} value={a3} onChange={(v)=>setAttributes({a3:v})}/><TextControl label={__('Question 4','restatify-base')} value={q4} onChange={(v)=>setAttributes({q4:v})}/><TextareaControl label={__('Answer 4','restatify-base')} value={a4} onChange={(v)=>setAttributes({a4:v})}/></PanelBody><PanelBody title={__('Content visibility','restatify-base')} initialOpen={false}><ToggleControl label={__('Show heading','restatify-base')} checked={showHeading!==false} onChange={(v)=>setAttributes({showHeading:!!v})}/></PanelBody></InspectorControls><div className={className} style={style}>{backgroundImageEnabled!==false&&overlayEnabled&&<div className="restatify-faq-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}<div className="container"><div className="row justify-content-center"><div className="col-12 col-lg-11"><div className="content-wrapper">{showHeading!==false&&<h2 className="mbr-section-title mbr-fonts-style display-2"><strong>{heading}</strong></h2>}<QA q={q1} a={a1}/><QA q={q2} a={a2}/><QA q={q3} a={a3}/><QA q={q4} a={a4}/></div></div></div></div></div></div>;
}

function Save({ attributes }) {
  const { backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen, heading, showHeading, q1, a1, q2, a2, q3, a3, q4, a4 } = attributes;
  const overlayStyle=getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
  const blockProps=useBlockProps.save({className:['restatify-faq-block', backgroundImageEnabled!==false&&parallax?'is-parallax':'', fullscreen?'is-fullscreen':''].join(' '), style:getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl)});
  return <div {...blockProps}>{backgroundImageEnabled!==false&&overlayEnabled&&<div className="restatify-faq-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}<div className="container"><div className="row justify-content-center"><div className="col-12 col-lg-11"><div className="content-wrapper">{showHeading!==false&&<h2 className="mbr-section-title mbr-fonts-style display-2"><strong>{heading}</strong></h2>}<QA q={q1} a={a1}/><QA q={q2} a={a2}/><QA q={q3} a={a3}/><QA q={q4} a={a4}/></div></div></div></div></div>;
}

registerBlockType(metadata.name,{...metadata,edit:Edit,save:Save});
