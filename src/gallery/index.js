import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { BackgroundLayoutPanel, getBackgroundStyle, getOverlayStyle } from '../shared/background-layout-controls';
import './editor.css';
import './style.css';

function ImageControl({ index, value, setAttributes }) {
  const key = `image${index}Url`;
  return <><p>{`Image ${index}`}</p><MediaUploadCheck><MediaUpload onSelect={(media)=>setAttributes({[key]:media?.url||''})} allowedTypes={['image']} value={value} render={({open})=><Button variant="secondary" onClick={open}>{value?__('Replace image','restatify-base'):__('Choose image','restatify-base')}</Button>}/></MediaUploadCheck>{value&&<Button variant="link" onClick={()=>setAttributes({[key]:''})}>{__('Remove image','restatify-base')}</Button>}</>;
}

function GalleryGrid({ urls }) { return <div className="restatify-gallery-grid">{urls.map((url,i)=>url?<img key={i} src={url} alt="" loading="lazy"/>:<div key={i} className="restatify-gallery-placeholder" aria-hidden="true"></div>)}</div>; }

function Edit({ attributes, setAttributes }) {
  const { backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen, image1Url, image2Url, image3Url, image4Url, image5Url, image6Url, image7Url, image8Url } = attributes;
  const className=['restatify-gallery-block', backgroundImageEnabled!==false&&parallax?'is-parallax':'', fullscreen?'is-fullscreen':''].join(' ');
  const style=getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl);
  const overlayStyle=getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
  const urls=[image1Url,image2Url,image3Url,image4Url,image5Url,image6Url,image7Url,image8Url];
  return <div {...useBlockProps()}><InspectorControls><BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes}/><PanelBody title={__('Images','restatify-base')} initialOpen={false}>{urls.map((v,i)=><ImageControl key={i} index={i+1} value={v} setAttributes={setAttributes}/>)}</PanelBody></InspectorControls><div className={className} style={style}>{backgroundImageEnabled!==false&&overlayEnabled&&<div className="restatify-gallery-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}<div className="container-fluid"><GalleryGrid urls={urls}/></div></div></div>;
}

function Save({ attributes }) {
  const { backgroundImageEnabled, backgroundImageUrl, overlayEnabled, overlayColor, overlayOpacity, parallax, fullscreen, image1Url, image2Url, image3Url, image4Url, image5Url, image6Url, image7Url, image8Url } = attributes;
  const overlayStyle=getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
  const blockProps=useBlockProps.save({className:['restatify-gallery-block', backgroundImageEnabled!==false&&parallax?'is-parallax':'', fullscreen?'is-fullscreen':''].join(' '), style:getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl)});
  return <div {...blockProps}>{backgroundImageEnabled!==false&&overlayEnabled&&<div className="restatify-gallery-bg-overlay" style={overlayStyle} aria-hidden="true"></div>}<div className="container-fluid"><GalleryGrid urls={[image1Url,image2Url,image3Url,image4Url,image5Url,image6Url,image7Url,image8Url]}/></div></div>;
}

registerBlockType(metadata.name,{...metadata,edit:Edit,save:Save});
