<?php
/**
 * Customizer helper values and sanitizers.
 */

/**
 * Return supported Customizer font choices.
 *
 * @return array<string,string> Map of CSS font-family values to translated labels.
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

/**
 * Sanitize a font choice against predefined options.
 *
 * @param mixed $value Candidate Customizer value.
 * @return string
 */
function restatify_sanitize_font_choice($value) {
    $choices = restatify_get_font_choices();
    if (isset($choices[$value])) {
        return $value;
    }

    return 'var(--wp--preset--font-family--ubuntu)';
}

/**
 * Sanitize a CSS font size value.
 *
 * @param mixed $value Candidate CSS value.
 * @return string
 */
function restatify_sanitize_font_size($value) {
    $value = trim((string) $value);
    if (preg_match('/^\d+(\.\d+)?(px|rem|em|%)$/', $value)) {
        return $value;
    }

    return '1rem';
}

/**
 * Sanitize a numeric line-height value.
 *
 * @param mixed $value Candidate line-height value.
 * @return string
 */
function restatify_sanitize_line_height($value) {
    $value = trim((string) $value);
    if (preg_match('/^\d+(\.\d+)?$/', $value)) {
        return $value;
    }

    return '1.5';
}

/**
 * Sanitize optional font size values.
 *
 * @param mixed $value Candidate value.
 * @return string
 */
function restatify_sanitize_optional_font_size($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    return restatify_sanitize_font_size($value);
}

/**
 * Sanitize optional line-height values.
 *
 * @param mixed $value Candidate value.
 * @return string
 */
function restatify_sanitize_optional_line_height($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    return restatify_sanitize_line_height($value);
}

/**
 * Sanitize the cookie consent message allowing safe HTML.
 *
 * @param mixed $value Candidate message.
 * @return string
 */
function restatify_sanitize_cookie_message($value) {
    return wp_kses_post((string) $value);
}

/**
 * Sanitize a footer URL.
 *
 * @param mixed $value Candidate URL.
 * @return string
 */
function restatify_sanitize_footer_url($value) {
    return esc_url_raw(trim((string) $value));
}

/**
 * Sanitize custom link targets used by maintenance settings.
 *
 * @param mixed $value Candidate URL or relative path.
 * @return string
 */
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

/**
 * Resolve a stored URL/path to a browser-facing URL.
 *
 * @param mixed $value URL or relative path.
 * @return string
 */
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

/**
 * Resolve a theme mod URL override and fall back to a default URL.
 *
 * @param string $theme_mod_key Theme mod key holding a link override.
 * @param string $fallback_url  Default URL to use when no override exists.
 * @return string
 */
function restatify_resolve_theme_mod_link_target(string $theme_mod_key, string $fallback_url): string {
    $custom_value = trim((string) get_theme_mod($theme_mod_key, ''));
    if ($custom_value === '') {
        return $fallback_url;
    }

    return (string) restatify_resolve_link_target($custom_value);
}

/**
 * Sanitize footer phone values.
 *
 * @param mixed $value Candidate phone value.
 * @return string
 */
function restatify_sanitize_footer_phone($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    return preg_replace('/[^0-9\+\s\-\(\)\.\/]/', '', $value);
}

/**
 * Build a tel: href from a phone value.
 *
 * @param mixed $value Candidate phone value.
 * @return string
 */
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

/**
 * Translate a string through Polylang when available.
 *
 * @param mixed $value Candidate string.
 * @return string
 */
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

/**
 * Build sanitized social links for footer variants.
 *
 * @return array<int,array{url:string,icon:string,label:string}>
 */
function restatify_get_footer_social_links(): array {
    $social_links = [];
    $social_candidates = [
        [
            'url' => (string) get_theme_mod('restatify_footer_social_linkedin', ''),
            'icon' => 'socicon-linkedin',
            'label' => 'LinkedIn',
        ],
        [
            'url' => (string) get_theme_mod('restatify_footer_social_xing', ''),
            'icon' => 'socicon-xing',
            'label' => 'Xing',
        ],
        [
            'url' => (string) get_theme_mod('restatify_footer_social_facebook', ''),
            'icon' => 'socicon-facebook',
            'label' => 'Facebook',
        ],
    ];

    foreach ($social_candidates as $candidate) {
        $url = esc_url($candidate['url']);
        if ($url !== '') {
            $social_links[] = [
                'url' => $url,
                'icon' => $candidate['icon'],
                'label' => $candidate['label'],
            ];
        }
    }

    return $social_links;
}

/**
 * Return localized default footer column configuration.
 *
 * @return array<int,array{title:string,links:array<int,array{title:string,url:string}>}>
 */
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

/**
 * Sanitize checkbox-style values.
 *
 * @param mixed $value Candidate checkbox value.
 * @return bool
 */
function restatify_sanitize_checkbox($value) {
    return !empty($value);
}

/**
 * Determine whether LightStart maintenance mode plugin is available.
 *
 * @return bool
 */
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
