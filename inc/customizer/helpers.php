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

function restatify_sanitize_cookie_message($value) {
    return wp_kses_post((string) $value);
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

function restatify_get_footer_column_defaults() {
    $current_lang = function_exists('pll_current_language') ? (string) pll_current_language('slug') : '';
    $locale = strtolower((string) get_locale());
    $is_german = $current_lang === 'de' || strpos($locale, 'de') === 0;

    $home_url = function_exists('pll_home_url') ? (string) pll_home_url() : home_url('/');
    $home_url = $home_url !== '' ? $home_url : home_url('/');

    $localize = static function (string $de, string $en) use ($is_german): string {
        return $is_german ? $de : $en;
    };

    $build_local_url = static function (string $path) use ($home_url): string {
        return trailingslashit($home_url) . ltrim($path, '/');
    };

    $contact_url = $build_local_url($is_german ? 'kontakt/' : 'contact/');
    $about_url = $build_local_url($is_german ? 'ueber-uns/' : 'about/');
    $services_url = $build_local_url($is_german ? 'leistungen/' : 'services/');
    $imprint_url = $build_local_url($is_german ? 'impressum/' : 'imprint/');

    $privacy_url = get_privacy_policy_url();
    if (empty($privacy_url)) {
        $privacy_url = $build_local_url($is_german ? 'datenschutz/' : 'privacy-policy/');
    }

    return [
        1 => [
            'title' => $localize('Unternehmen', 'Company'),
            'links' => [
                ['title' => $localize('Ueber uns', 'About'), 'url' => $about_url],
                ['title' => $localize('Kontakt', 'Contact'), 'url' => $contact_url],
                ['title' => '', 'url' => ''],
                ['title' => '', 'url' => ''],
            ],
        ],
        2 => [
            'title' => $localize('Leistungen', 'Services'),
            'links' => [
                ['title' => $localize('Beratung', 'Consulting'), 'url' => $services_url],
                ['title' => $localize('Startseite', 'Home'), 'url' => $home_url],
                ['title' => '', 'url' => ''],
                ['title' => '', 'url' => ''],
            ],
        ],
        3 => [
            'title' => $localize('Rechtliches', 'Legal'),
            'links' => [
                ['title' => $localize('Datenschutz', 'Privacy Policy'), 'url' => $privacy_url],
                ['title' => $localize('Impressum', 'Imprint'), 'url' => $imprint_url],
                ['title' => '', 'url' => ''],
                ['title' => '', 'url' => ''],
            ],
        ],
    ];
}

function restatify_sanitize_checkbox($value) {
    return !empty($value);
}

function restatify_is_lightstart_available(): bool {
    if (class_exists('\\Restatify\\Shared\\Runtime\\PluginState', false)) {
        return \Restatify\Shared\Runtime\PluginState::isLightstartAvailable();
    }

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
