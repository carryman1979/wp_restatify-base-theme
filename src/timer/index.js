import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, ToggleControl, DateTimePicker, Button } from '@wordpress/components';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import { BackgroundLayoutPanel, getBackgroundStyle, getOverlayStyle } from '../shared/background-layout-controls';
import './editor.css';
import './style.css';

const UNIT_CONFIG = [
    { key: 'years', singular: __('Jahr', 'restatify-base'), plural: __('Jahre', 'restatify-base') },
    { key: 'months', singular: __('Monat', 'restatify-base'), plural: __('Monate', 'restatify-base') },
    { key: 'days', singular: __('Tag', 'restatify-base'), plural: __('Tage', 'restatify-base') },
    { key: 'hours', singular: __('Stunde', 'restatify-base'), plural: __('Stunden', 'restatify-base') },
    { key: 'minutes', singular: __('Minute', 'restatify-base'), plural: __('Minuten', 'restatify-base') },
    { key: 'seconds', singular: __('Sekunde', 'restatify-base'), plural: __('Sekunden', 'restatify-base') }
];

function toInt(value) {
    const parsed = Number(value);

    if (!Number.isFinite(parsed)) {
        return 0;
    }

    return Math.max(0, Math.floor(parsed));
}

function formatPickerValue(dateValue) {
    if (!dateValue) {
        return '';
    }

    const parsedMs = Date.parse(dateValue);

    if (!Number.isFinite(parsedMs)) {
        return '';
    }

    const date = new Date(parsedMs);
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
}

function getTargetDate(targetDateTime) {
    const parsedMs = Date.parse(targetDateTime || '');

    if (!Number.isFinite(parsedMs)) {
        return null;
    }

    return new Date(parsedMs);
}

function advanceCursor(cursor, unitKey) {
    const next = new Date(cursor.getTime());

    if (unitKey === 'years') {
        next.setFullYear(next.getFullYear() + 1);
        return next;
    }

    if (unitKey === 'months') {
        next.setMonth(next.getMonth() + 1);
        return next;
    }

    if (unitKey === 'days') {
        next.setDate(next.getDate() + 1);
        return next;
    }

    if (unitKey === 'hours') {
        next.setHours(next.getHours() + 1);
        return next;
    }

    if (unitKey === 'minutes') {
        next.setMinutes(next.getMinutes() + 1);
        return next;
    }

    next.setSeconds(next.getSeconds() + 1);
    return next;
}

function getUnitLabel(unit, value) {
    return value === 1 ? unit.singular : unit.plural;
}

function getDisplayUnits(nowDate, targetDate) {
    if (!(targetDate instanceof Date) || targetDate <= nowDate) {
        return [{ key: 'seconds', label: __('Sekunden', 'restatify-base'), value: 0 }];
    }

    const cursor = new Date(nowDate.getTime());
    const values = {
        years: 0,
        months: 0,
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0
    };

    UNIT_CONFIG.forEach((unit) => {
        let next = advanceCursor(cursor, unit.key);

        while (next <= targetDate) {
            values[unit.key] += 1;
            cursor.setTime(next.getTime());
            next = advanceCursor(cursor, unit.key);
        }
    });

    const units = UNIT_CONFIG.map((unit) => ({
        key: unit.key,
        label: getUnitLabel(unit, values[unit.key]),
        value: values[unit.key]
    }));
    let startIndex = units.findIndex((unit, index) => unit.value > 0 && index < units.length - 1);

    if (startIndex < 0) {
        startIndex = units.length - 1;
    }

    return units.slice(startIndex);
}

function PreviewTimer({ targetDateTime }) {
    const targetDate = getTargetDate(targetDateTime);

    if (!targetDate) {
        return (
            <p className="restatify-timer-empty mbr-fonts-style display-7">
                {__('Bitte einen gueltigen Zielzeitpunkt waehlen.', 'restatify-base')}
            </p>
        );
    }

    const units = getDisplayUnits(new Date(), targetDate);

    return (
        <div className="restatify-timer-grid" aria-live="polite">
            {units.map((unit) => (
                <div className="restatify-timer-item" key={unit.key} data-unit={unit.key}>
                    <p className="restatify-timer-value mbr-fonts-style display-2"><strong>{unit.value}</strong></p>
                    <p className="restatify-timer-label mbr-fonts-style display-7">{unit.label}</p>
                </div>
            ))}
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
        title,
        subtitle,
        description,
        showTitle,
        showSubtitle,
        showDescription,
        targetDateTime
    } = attributes;

    const blockClassName = [
        'restatify-timer-block',
        backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
        fullscreen ? 'is-fullscreen' : ''
    ].join(' ');
    const blockStyle = getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl);
    const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);

    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <BackgroundLayoutPanel attributes={attributes} setAttributes={setAttributes} />

                <PanelBody title={__('Inhalt', 'restatify-base')} initialOpen={false}>
                    <TextControl
                        label={__('Titel', 'restatify-base')}
                        value={title}
                        onChange={(value) => setAttributes({ title: value })}
                    />
                    <TextControl
                        label={__('Untertitel', 'restatify-base')}
                        value={subtitle}
                        onChange={(value) => setAttributes({ subtitle: value })}
                    />
                    <TextareaControl
                        label={__('Beschreibung', 'restatify-base')}
                        value={description}
                        onChange={(value) => setAttributes({ description: value })}
                    />
                </PanelBody>

                <PanelBody title={__('Zielzeitpunkt', 'restatify-base')} initialOpen={true}>
                    <div className="restatify-timer-datetime-fix">
                        <DateTimePicker
                            currentDate={targetDateTime || null}
                            onChange={(nextValue) => setAttributes({ targetDateTime: nextValue || '' })}
                            is12Hour={false}
                        />
                    </div>
                    <p className="restatify-timer-picker-hint">
                        {targetDateTime
                            ? `${__('Aktueller Zielwert:', 'restatify-base')} ${formatPickerValue(targetDateTime)}`
                            : __('Noch kein Zielzeitpunkt gesetzt.', 'restatify-base')}
                    </p>
                    {targetDateTime && (
                        <Button
                            variant="link"
                            onClick={() => setAttributes({ targetDateTime: '' })}
                        >
                            {__('Zielzeitpunkt entfernen', 'restatify-base')}
                        </Button>
                    )}
                </PanelBody>

                <PanelBody title={__('Sichtbarkeit', 'restatify-base')} initialOpen={false}>
                    <ToggleControl
                        label={__('Titel anzeigen', 'restatify-base')}
                        checked={showTitle !== false}
                        onChange={(value) => setAttributes({ showTitle: !!value })}
                    />
                    <ToggleControl
                        label={__('Untertitel anzeigen', 'restatify-base')}
                        checked={showSubtitle !== false}
                        onChange={(value) => setAttributes({ showSubtitle: !!value })}
                    />
                    <ToggleControl
                        label={__('Beschreibung anzeigen', 'restatify-base')}
                        checked={showDescription !== false}
                        onChange={(value) => setAttributes({ showDescription: !!value })}
                    />
                </PanelBody>
            </InspectorControls>

            <div className={blockClassName} style={blockStyle}>
                {backgroundImageEnabled !== false && overlayEnabled && (
                    <div className="restatify-timer-bg-overlay" style={overlayStyle} aria-hidden="true"></div>
                )}
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-12 col-lg-10">
                            <div className="restatify-timer-card">
                                {showTitle !== false && (
                                    <h2 className="restatify-timer-title mbr-fonts-style display-2"><strong>{title}</strong></h2>
                                )}
                                {showSubtitle !== false && (
                                    <h3 className="restatify-timer-subtitle mbr-fonts-style display-5">{subtitle}</h3>
                                )}
                                {showDescription !== false && (
                                    <p className="restatify-timer-description mbr-fonts-style display-7">{description}</p>
                                )}
                                <PreviewTimer targetDateTime={targetDateTime} />
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
        title,
        subtitle,
        description,
        showTitle,
        showSubtitle,
        showDescription,
        targetDateTime
    } = attributes;

    const targetDate = getTargetDate(targetDateTime);
    const previewUnits = targetDate ? getDisplayUnits(new Date(), targetDate) : [];
    const parsedTargetMs = Date.parse(targetDateTime || '');
    const overlayStyle = getOverlayStyle(overlayEnabled, overlayColor, overlayOpacity);
    const blockProps = useBlockProps.save({
        className: [
            'restatify-timer-block',
            backgroundImageEnabled !== false && parallax ? 'is-parallax' : '',
            fullscreen ? 'is-fullscreen' : ''
        ].join(' '),
        style: getBackgroundStyle(backgroundImageEnabled, backgroundImageUrl)
    });

    return (
        <div {...blockProps}>
            {backgroundImageEnabled !== false && overlayEnabled && (
                <div className="restatify-timer-bg-overlay" style={overlayStyle} aria-hidden="true"></div>
            )}
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-12 col-lg-10">
                        <div className="restatify-timer-card">
                            {showTitle !== false && (
                                <h2 className="restatify-timer-title mbr-fonts-style display-2"><strong>{title}</strong></h2>
                            )}
                            {showSubtitle !== false && (
                                <h3 className="restatify-timer-subtitle mbr-fonts-style display-5">{subtitle}</h3>
                            )}
                            {showDescription !== false && (
                                <p className="restatify-timer-description mbr-fonts-style display-7">{description}</p>
                            )}

                            <div
                                className="restatify-countdown"
                                data-target-ms={Number.isFinite(parsedTargetMs) ? String(parsedTargetMs) : ''}
                                aria-live="polite"
                            >
                                {previewUnits.length ? (
                                    <div className="restatify-timer-grid" data-role="grid">
                                        {previewUnits.map((unit) => (
                                            <div className="restatify-timer-item" data-unit={unit.key} key={unit.key}>
                                                <p className="restatify-timer-value mbr-fonts-style display-2" data-role="value"><strong>{unit.value}</strong></p>
                                                <p className="restatify-timer-label mbr-fonts-style display-7">{unit.label}</p>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <p className="restatify-timer-empty mbr-fonts-style display-7" data-role="empty">
                                        {__('Bitte einen gueltigen Zielzeitpunkt waehlen.', 'restatify-base')}
                                    </p>
                                )}
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
