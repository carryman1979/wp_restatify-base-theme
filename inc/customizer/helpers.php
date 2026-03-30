<?php
/**
 * Customizer helper values and sanitizers.
 */

function restatify_get_font_choices() {
    return [
        'var(--wp--preset--font-family--ubuntu)' => __('Ubuntu (Theme)', 'restatify-base'),
        'var(--wp--preset--font-family--science-gothic)' => __('Science Gothic (Theme)', 'restatify-base'),
        'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif' => __('System UI', 'restatify-base'),
        'Arial, Helvetica, sans-serif' => __('Arial', 'restatify-base'),
        '"Trebuchet MS", "Segoe UI", sans-serif' => __('Trebuchet', 'restatify-base'),
        'Georgia, "Times New Roman", serif' => __('Georgia', 'restatify-base'),
        '"Courier New", Courier, monospace' => __('Courier New', 'restatify-base'),
    ];
}

function restatify_sanitize_font_choice($value) {
    $choices = restatify_get_font_choices();
    if (isset($choices[$value])) {
        return $value;
    }

    return 'var(--wp--preset--font-family--ubuntu)';
}

function restatify_sanitize_font_size($value) {
    $value = trim((string) $value);
    if (preg_match('/^\d+(\.\d+)?(px|rem|em|%)$/', $value)) {
        return $value;
    }

    return '1rem';
}

function restatify_sanitize_line_height($value) {
    $value = trim((string) $value);
    if (preg_match('/^\d+(\.\d+)?$/', $value)) {
        return $value;
    }

    return '1.5';
}

function restatify_sanitize_optional_font_size($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    return restatify_sanitize_font_size($value);
}

function restatify_sanitize_optional_line_height($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    return restatify_sanitize_line_height($value);
}

function restatify_sanitize_footer_url($value) {
    return esc_url_raw(trim((string) $value));
}

function restatify_sanitize_footer_phone($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    return preg_replace('/[^0-9\+\s\-\(\)\.\/]/', '', $value);
}

function restatify_get_tel_href($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    $value = preg_replace('/[^0-9\+]/', '', $value);
    if ($value === '') {
        return '';
    }

    return 'tel:' . $value;
}

function restatify_translate_polylang_string($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    if (function_exists('pll__')) {
        $translated = pll__($value);
        if (is_string($translated) && $translated !== '') {
            return $translated;
        }
    }

    return $value;
}
