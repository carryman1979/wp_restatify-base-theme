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

function restatify_sanitize_link_target($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    if (preg_match('#^(https?:)?//#i', $value)) {
        return esc_url_raw($value);
    }

    if (preg_match('#^(mailto:|tel:)#i', $value)) {
        return esc_url_raw($value);
    }

    // Relative file/path from WordPress installation root.
    return ltrim($value, '/');
}

function restatify_resolve_link_target($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    if (preg_match('#^(https?:)?//#i', $value) || preg_match('#^(mailto:|tel:)#i', $value)) {
        return esc_url($value);
    }

    $relative = ltrim($value, '/');

    // Prefer file paths that actually exist in WordPress root.
    if (defined('ABSPATH') && is_file(trailingslashit(ABSPATH) . $relative)) {
        return esc_url(home_url('/' . $relative));
    }

    // Fallback: if the file lives in the current theme root, resolve to theme URL.
    if (function_exists('get_theme_file_path') && function_exists('get_theme_file_uri') && is_file(get_theme_file_path('/' . $relative))) {
        return esc_url(get_theme_file_uri('/' . $relative));
    }

    return esc_url(home_url('/' . $relative));
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

function restatify_sanitize_checkbox($value) {
    return !empty($value);
}

function restatify_is_lightstart_available(): bool {
    if (! defined('WP_PLUGIN_DIR')) {
        return false;
    }

    $plugin_file = WP_PLUGIN_DIR . '/wp-maintenance-mode/wp-maintenance-mode.php';
    if (! file_exists($plugin_file)) {
        return false;
    }

    $active_plugins = (array) get_option('active_plugins', []);
    if (in_array('wp-maintenance-mode/wp-maintenance-mode.php', $active_plugins, true)) {
        return true;
    }

    if (is_multisite()) {
        $network_plugins = (array) get_site_option('active_sitewide_plugins', []);
        return isset($network_plugins['wp-maintenance-mode/wp-maintenance-mode.php']);
    }

    return false;
}
