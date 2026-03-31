import { __ } from '@wordpress/i18n';
import { ColorPalette, ColorPicker } from '@wordpress/components';

export function getThemeColorPresets() {
	if (typeof window === 'undefined' || !window.getComputedStyle || !window.document?.documentElement) {
		return [
			{ name: 'Primary', color: '#ff6b00' },
			{ name: 'Primary Hover', color: '#a84700' },
			{ name: 'Secondary', color: '#00c2ff' },
			{ name: 'Secondary Hover', color: '#0080a8' },
			{ name: 'Background', color: '#f8fafc' },
			{ name: 'Text', color: '#0b1221' },
			{ name: 'Menu Panel', color: '#ffffff' }
		];
	}

	const rootStyle = window.getComputedStyle(window.document.documentElement);
	const fallbackMap = {
		'--rs-color-primary': '#ff6b00',
		'--rs-color-primary-hover': '#a84700',
		'--rs-color-secondary': '#00c2ff',
		'--rs-color-secondary-hover': '#0080a8',
		'--rs-color-background': '#f8fafc',
		'--rs-color-text': '#0b1221',
		'--rs-color-menu-panel': '#ffffff'
	};

	const presetConfig = [
		{ name: 'Primary', variable: '--rs-color-primary' },
		{ name: 'Primary Hover', variable: '--rs-color-primary-hover' },
		{ name: 'Secondary', variable: '--rs-color-secondary' },
		{ name: 'Secondary Hover', variable: '--rs-color-secondary-hover' },
		{ name: 'Background', variable: '--rs-color-background' },
		{ name: 'Text', variable: '--rs-color-text' },
		{ name: 'Menu Panel', variable: '--rs-color-menu-panel' }
	];

	return presetConfig.map(({ name, variable }) => ({
		name,
		color: rootStyle.getPropertyValue(variable).trim() || fallbackMap[variable]
	}));
}

export function ThemeColorControl({ label, value, onChange, themeColorPresets, fallbackColor = '#ffffff' }) {
	return (
		<>
			<p>{label}</p>
			<ColorPalette
				colors={themeColorPresets}
				value={value || ''}
				onChange={(nextColor) => onChange(nextColor || '')}
				disableCustomColors={false}
				clearable={false}
			/>
			<ColorPicker
				color={value || fallbackColor}
				onChangeComplete={(nextValue) => onChange(nextValue?.hex || '')}
				disableAlpha={true}
			/>
		</>
	);
}

export function getResetButtonLabel() {
	return __('Reset colors to default', 'restatify-base');
}
