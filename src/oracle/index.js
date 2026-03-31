import { __ } from '@wordpress/i18n';
import {
    useBlockProps,
    InspectorControls,
    MediaUpload,
    MediaUploadCheck
} from '@wordpress/block-editor';
import {
    PanelBody,
    TextControl,
    TextareaControl,
    RangeControl,
    ToggleControl,
    Button
} from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import {
    BackgroundLayoutPanel,
    getBackgroundStyle,
    getOverlayStyle
} from '../shared/background-layout-controls';
import { getThemeColorPresets, ThemeColorControl } from '../shared/theme-color-controls';
import './editor.css';
import './style.css';

function parseWordPairs(rawText) {
    return (rawText || '')
        .split('\n')
        .map((line) => line.trim())
        .filter(Boolean)
        .map((line) => {
            const parts = line.split('|').map((part) => part.trim()).filter(Boolean);

            if (parts.length === 1) {
                return [parts[0], parts[0]];
            }

            return [parts[0], parts[1]];
        })
        .filter((pair) => pair[0] && pair[1]);
}

function getOracleStyleVariables(attributes) {
    const {
        wordPrimaryColor,
        wordSecondaryColor,
        glowColor,
        shellStartColor,
        shellEndColor
    } = attributes;
    const vars = {};

    if (wordPrimaryColor) {
        vars['--rs-oracle-word-primary'] = wordPrimaryColor;
    }

    if (wordSecondaryColor) {
        vars['--rs-oracle-word-secondary'] = wordSecondaryColor;
    }

    if (glowColor) {
        vars['--rs-oracle-glow'] = glowColor;
    }

    if (shellStartColor) {
        vars['--rs-oracle-shell-start'] = shellStartColor;
    }

    if (shellEndColor) {
        vars['--rs-oracle-shell-end'] = shellEndColor;
    }

    return vars;
}

function PreviewPair({ pair }) {
    return (
        <div className="restatify-oracle-pair-frame is-visible" aria-live="polite">
            <p className="restatify-oracle-word restatify-oracle-word-left is-visible">{pair[0]}</p>
            <p className="restatify-oracle-word restatify-oracle-word-right is-visible">{pair[1]}</p>
        </div>
    );
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
        wordPairsText,
        randomMode,
        cycleMs,
        logoIntervalMs,
        logoVisibleMs,
        driftRange,
        wordPrimaryColor,
        wordSecondaryColor,
        glowColor,
        shellStartColor,
        shellEndColor,
        logoFallbackUrl
    } = attributes;

    const pairs = parseWordPairs(wordPairsText);
    const firstPair = pairs[0] || ['VISION', 'SINGULARITY'];
    const themeColorPresets = useMemo(() => getThemeColorPresets(), []);
    const blockClassName = [
        'restatify-oracle-block',
        backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
        fullscreen ? 'is-fullscreen' : ''
    ].join(' ');
    const blockStyle = {
        ...getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl),
        ...getOracleStyleVariables(attributes)
    };
    const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);

    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes} />

                <PanelBody title={__('Content', 'restatify-base')} initialOpen={false}>
                    <TextControl
                        label={__('Label', 'restatify-base')}
                        value={label}
                        onChange={(nextValue) => setAttributes({ label: nextValue })}
                    />
                    <TextareaControl
                        label={__('Word pairs (one pair per line, separated by |)', 'restatify-base')}
                        help={__('Example: VISION|SINGULARITY', 'restatify-base')}
                        value={wordPairsText}
                        onChange={(nextValue) => setAttributes({ wordPairsText: nextValue })}
                    />
                    <ToggleControl
                        label={__('Random mode', 'restatify-base')}
                        checked={!!randomMode}
                        onChange={(nextValue) => setAttributes({ randomMode: !!nextValue })}
                        help={__('If disabled, pairs are shown in order.', 'restatify-base')}
                    />
                    <RangeControl
                        label={__('Word cycle duration (ms)', 'restatify-base')}
                        value={Number(cycleMs) || 4200}
                        onChange={(nextValue) => setAttributes({ cycleMs: Number(nextValue) || 4200 })}
                        min={1800}
                        max={9000}
                        step={200}
                    />
                    <RangeControl
                        label={__('Logo interval (ms)', 'restatify-base')}
                        value={Number(logoIntervalMs) || 300000}
                        onChange={(nextValue) => setAttributes({ logoIntervalMs: Number(nextValue) || 300000 })}
                        min={30000}
                        max={600000}
                        step={5000}
                    />
                    <RangeControl
                        label={__('Logo visible duration (ms)', 'restatify-base')}
                        value={Number(logoVisibleMs) || 3500}
                        onChange={(nextValue) => setAttributes({ logoVisibleMs: Number(nextValue) || 3500 })}
                        min={800}
                        max={12000}
                        step={100}
                    />
                    <RangeControl
                        label={__('Position drift range (%)', 'restatify-base')}
                        value={Number(driftRange) || 14}
                        onChange={(nextValue) => setAttributes({ driftRange: Number(nextValue) || 14 })}
                        min={4}
                        max={24}
                        step={1}
                    />
                </PanelBody>

                <PanelBody title={__('Style tuning', 'restatify-base')} initialOpen={false}>
                    <ThemeColorControl
                        label={__('Word 1 color', 'restatify-base')}
                        value={wordPrimaryColor}
                        onChange={(nextValue) => setAttributes({ wordPrimaryColor: nextValue })}
                        themeColorPresets={themeColorPresets}
                        fallbackColor="#f3f7ff"
                    />
                    <ThemeColorControl
                        label={__('Word 2 color', 'restatify-base')}
                        value={wordSecondaryColor}
                        onChange={(nextValue) => setAttributes({ wordSecondaryColor: nextValue })}
                        themeColorPresets={themeColorPresets}
                        fallbackColor="#b9ccff"
                    />
                    <ThemeColorControl
                        label={__('Glow color', 'restatify-base')}
                        value={glowColor}
                        onChange={(nextValue) => setAttributes({ glowColor: nextValue })}
                        themeColorPresets={themeColorPresets}
                        fallbackColor="#6cf2ff"
                    />
                    <ThemeColorControl
                        label={__('Shell gradient start', 'restatify-base')}
                        value={shellStartColor}
                        onChange={(nextValue) => setAttributes({ shellStartColor: nextValue })}
                        themeColorPresets={themeColorPresets}
                        fallbackColor="#7237ff"
                    />
                    <ThemeColorControl
                        label={__('Shell gradient end', 'restatify-base')}
                        value={shellEndColor}
                        onChange={(nextValue) => setAttributes({ shellEndColor: nextValue })}
                        themeColorPresets={themeColorPresets}
                        fallbackColor="#070a1a"
                    />
                </PanelBody>

                <PanelBody title={__('Logo overlay fallback (optional)', 'restatify-base')} initialOpen={false}>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={(media) => setAttributes({ logoFallbackUrl: media?.url || '' })}
                            allowedTypes={['image']}
                            value={logoFallbackUrl}
                            render={({ open }) => (
                                <Button variant="secondary" onClick={open}>
                                    {logoFallbackUrl
                                        ? __('Replace fallback logo', 'restatify-base')
                                        : __('Choose fallback logo', 'restatify-base')}
                                </Button>
                            )}
                        />
                    </MediaUploadCheck>
                    {logoFallbackUrl && (
                        <Button variant="link" onClick={() => setAttributes({ logoFallbackUrl: '' })}>
                            {__('Remove fallback logo', 'restatify-base')}
                        </Button>
                    )}
                </PanelBody>
            </InspectorControls>

            <div className={blockClassName} style={blockStyle}>
                {backgroundImageEnabled !== false && overlayEnabled && (
                    <div className="restatify-oracle-bg-overlay" style={overlayStyle} aria-hidden="true"></div>
                )}
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-12 col-lg-11">
                            <div className="restatify-oracle-shell">
                                <p className="restatify-oracle-label mbr-fonts-style display-4">{label}</p>
                                <div className="restatify-oracle-stage">
                                    <PreviewPair pair={firstPair} />
                                    <div className="restatify-oracle-scan" aria-hidden="true"></div>
                                    <div className="restatify-oracle-logo-overlay" aria-hidden="true">
                                        {logoFallbackUrl
                                            ? <img className="restatify-oracle-logo" src={logoFallbackUrl} alt="" />
                                            : <span className="restatify-oracle-logo-fallback">LOGO</span>}
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
        wordPairsText,
        randomMode,
        cycleMs,
        logoIntervalMs,
        logoVisibleMs,
        driftRange,
        logoFallbackUrl
    } = attributes;

    const pairs = parseWordPairs(wordPairsText);
    const seedPair = pairs[0] || ['VISION', 'SINGULARITY'];
    const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
    const blockProps = useBlockProps.save({
        className: [
            'restatify-oracle-block',
            backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
            fullscreen ? 'is-fullscreen' : ''
        ].join(' '),
        style: {
            ...getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl),
            ...getOracleStyleVariables(attributes)
        }
    });

    return (
        <div {...blockProps}>
            {backgroundImageEnabled !== false && overlayEnabled && (
                <div className="restatify-oracle-bg-overlay" style={overlayStyle} aria-hidden="true"></div>
            )}
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-12 col-lg-11">
                        <div className="restatify-oracle-shell">
                            <p className="restatify-oracle-label mbr-fonts-style display-4">{label}</p>
                            <div
                                className="restatify-oracle-stage"
                                data-word-pairs={JSON.stringify(pairs)}
                                data-random-mode={randomMode ? '1' : '0'}
                                data-cycle-ms={String(Number(cycleMs) || 4200)}
                                data-logo-interval-ms={String(Number(logoIntervalMs) || 300000)}
                                data-logo-visible-ms={String(Number(logoVisibleMs) || 3500)}
                                data-drift-range={String(Number(driftRange) || 14)}
                                data-logo-fallback={logoFallbackUrl || ''}
                            >
                                <div className="restatify-oracle-pair-frame is-visible" data-role="pair-frame">
                                    <p className="restatify-oracle-word restatify-oracle-word-left is-visible" data-role="word-left">{seedPair[0]}</p>
                                    <p className="restatify-oracle-word restatify-oracle-word-right" data-role="word-right">{seedPair[1]}</p>
                                </div>
                                <div className="restatify-oracle-scan" aria-hidden="true"></div>
                                <div className="restatify-oracle-logo-overlay" data-role="logo-overlay" aria-hidden="true">
                                    <img className="restatify-oracle-logo" data-role="logo-image" src="" alt="" />
                                    <span className="restatify-oracle-logo-fallback" data-role="logo-fallback">RESTATIFY</span>
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
