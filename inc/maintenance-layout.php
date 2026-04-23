<?php
/**
 * Maintenance / coming soon layout helpers.
 *
 * This module controls how the theme integrates with LightStart (WP Maintenance Mode)
 * for both real maintenance mode and normal public requests that still use the
 * LightStart page template.
 */

function restatify_use_maintenance_layout(): bool {
    $theme_override_enabled = (bool) apply_filters('restatify_enable_theme_maintenance_layout', false);
    $theme_override_enabled = (bool) apply_filters('restatify_enable_theme_maintenance_chrome', $theme_override_enabled);
    if (! $theme_override_enabled) {
        return false;
    }

    $enabled = restatify_is_lightstart_maintenance_request();

    $enabled = (bool) apply_filters('restatify_use_maintenance_layout', $enabled);

    return (bool) apply_filters('restatify_use_minimal_chrome', $enabled);
}

function restatify_use_minimal_chrome(): bool {
    return restatify_use_maintenance_layout();
}

function restatify_is_lightstart_maintenance_request(): bool {
    if (is_admin()) {
        return false;
    }

    if (defined('IS_MAINTENANCE') && IS_MAINTENANCE) {
        return true;
    }

    if (! class_exists('WP_Maintenance_Mode')) {
        return false;
    }

    $settings = get_option('wpmm_settings');
    if (! is_array($settings)) {
        return false;
    }

    $status = (int) ($settings['general']['status'] ?? 0);
    if ($status !== 1) {
        return false;
    }

    $page_id = (int) ($settings['design']['page_id'] ?? 0);
    if ($page_id > 0 && function_exists('is_page') && is_page($page_id)) {
        return true;
    }

    return false;
}

function restatify_filter_template_part_block(array $parsed_block): array {
    if (! restatify_use_maintenance_layout()) {
        return $parsed_block;
    }

    if (($parsed_block['blockName'] ?? '') !== 'core/template-part') {
        return $parsed_block;
    }

    $slug = $parsed_block['attrs']['slug'] ?? '';
    if ($slug === 'header') {
        $parsed_block['attrs']['slug'] = 'header-minimal';
    }

    if ($slug === 'footer') {
        $parsed_block['attrs']['slug'] = 'footer-minimal';
    }

    return $parsed_block;
}
add_filter('render_block_data', 'restatify_filter_template_part_block', 10, 1);

function restatify_should_inject_lightstart_chrome(): bool {
    if (! restatify_is_lightstart_maintenance_request()) {
        return false;
    }

    $enabled = (bool) apply_filters('restatify_enable_lightstart_maintenance_chrome', true);
    if (! $enabled) {
        return false;
    }

    $enabled = (bool) apply_filters('restatify_use_lightstart_maintenance_chrome', $enabled);
    if (! $enabled) {
        return false;
    }

    if (! class_exists('WP_Maintenance_Mode')) {
        return false;
    }

    return true;
}

function restatify_is_lightstart_page_template_request(): bool {
    if (is_admin()) {
        return false;
    }

    if (! class_exists('WP_Maintenance_Mode')) {
        return false;
    }

    if (! function_exists('is_page') || ! is_page()) {
        return false;
    }

    $queried_id = (int) get_queried_object_id();
    if ($queried_id <= 0) {
        return false;
    }

    return get_page_template_slug($queried_id) === 'templates/wpmm-page-template.php';
}

/**
 * Determine whether template chrome should be injected for the current request.
 *
 * Real maintenance mode and public LightStart template requests both need
 * explicit header/footer output because the LightStart template bypasses the
 * normal block-theme template part rendering flow.
 */
function restatify_should_inject_lightstart_template_chrome(): bool {
    if (restatify_should_inject_lightstart_chrome()) {
        return true;
    }

    return restatify_is_lightstart_page_template_request();
}

/**
 * Prevent duplicate head/style output on block themes when maintenance is off.
 *
 * LightStart registers an FSE workaround that calls wp_head() in both head and
 * footer hooks. That behavior is useful in maintenance mode but produces duplicate
 * localized scripts/meta in normal public mode. We disable those hooks only for
 * the public LightStart template request.
 */
function restatify_disable_lightstart_fse_style_buffer_on_public_template(): void {
    if (! restatify_is_lightstart_page_template_request()) {
        return;
    }

    // Keep LightStart's FSE workaround for real maintenance mode only.
    if (restatify_is_lightstart_maintenance_request()) {
        return;
    }

    if (! class_exists('WP_Maintenance_Mode')) {
        return;
    }

    $instance = WP_Maintenance_Mode::get_instance();
    if (! is_object($instance)) {
        return;
    }

    remove_action('wpmm_head', [$instance, 'remember_style_fse']);
    remove_action('wpmm_footer', [$instance, 'add_style_fse']);
}
add_action('template_redirect', 'restatify_disable_lightstart_fse_style_buffer_on_public_template', 0);

function restatify_is_plugin_active(string $plugin_relative_path): bool {
    if ($plugin_relative_path === '') {
        return false;
    }

    $active_plugins = (array) get_option('active_plugins', []);
    if (in_array($plugin_relative_path, $active_plugins, true)) {
        return true;
    }

    if (is_multisite()) {
        $network_plugins = (array) get_site_option('active_sitewide_plugins', []);
        return isset($network_plugins[$plugin_relative_path]);
    }

    return false;
}

function restatify_is_booking_assistant_active_in_maintenance(): bool {
    if (! restatify_is_plugin_active('wp_restatify-booking-assistant/wp_restatify-booking-assistant.php')) {
        return false;
    }

    $options = get_option('restatify_booking_assistant_options', []);
    if (! is_array($options)) {
        return false;
    }

    return empty($options['disable_during_maintenance']);
}

function restatify_is_multi_chat_overlay_active_in_maintenance(): bool {
    if (! restatify_is_plugin_active('wp_restatify-multi-chat-overlay/wp_restatify-multi-chat-overlay.php')) {
        return false;
    }

    $options = get_option('restatify_multi_chat_overlay_options', []);
    if (! is_array($options)) {
        return false;
    }

    if (empty($options['enabled']) || ! empty($options['disable_during_maintenance'])) {
        return false;
    }

    $has_own_chat = ! empty($options['own_chat_enabled']);
    $has_channels = false;
    foreach ((array) ($options['channels'] ?? []) as $channel_url) {
        if (trim((string) $channel_url) !== '') {
            $has_channels = true;
            break;
        }
    }

    return $has_own_chat || $has_channels;
}

function restatify_should_show_cookie_banner_in_maintenance(): bool {
    if (! restatify_is_lightstart_maintenance_request()) {
        return true;
    }

    $should_show = restatify_is_booking_assistant_active_in_maintenance()
        || restatify_is_multi_chat_overlay_active_in_maintenance();

    return (bool) apply_filters('restatify_show_cookie_banner_in_maintenance', $should_show);
}

function restatify_render_minimal_header_markup(): string {
    return restatify_capture_pattern_markup('/patterns/restatify-base-header-minimal.php');
}

function restatify_render_minimal_footer_markup(): string {
    return restatify_capture_pattern_markup('/patterns/restatify-base-footer-minimal.php');
}

function restatify_render_default_header_markup(): string {
    return restatify_capture_pattern_markup('/patterns/restatify-base-header.php');
}

function restatify_render_default_footer_markup(): string {
    return restatify_capture_pattern_markup('/patterns/restatify-base-footer.php');
}

function restatify_render_lightstart_header_markup(): string {
    if (restatify_should_inject_lightstart_chrome()) {
        return restatify_render_minimal_header_markup();
    }

    return restatify_render_default_header_markup();
}

/**
 * Render footer chrome according to runtime mode.
 *
 * Maintenance mode keeps minimal legal/social footer, while non-maintenance
 * public mode restores the full theme footer.
 */
function restatify_render_lightstart_footer_markup(): string {
    if (restatify_should_inject_lightstart_chrome()) {
        return restatify_render_minimal_footer_markup();
    }

    return restatify_render_default_footer_markup();
}

function restatify_capture_pattern_markup(string $relative_path): string {
    $file = get_template_directory() . $relative_path;
    if (! file_exists($file)) {
        return '';
    }

    ob_start();
    include $file;

    return (string) ob_get_clean();
}

function restatify_output_lightstart_minimal_header(): void {
    static $header_rendered = false;

    if ($header_rendered || ! restatify_should_inject_lightstart_template_chrome()) {
        return;
    }

    $markup = restatify_render_lightstart_header_markup();
    if ($markup === '') {
        return;
    }

    echo $markup;
    $header_rendered = true;
}

function restatify_output_lightstart_minimal_footer(): void {
    static $footer_rendered = false;

    if ($footer_rendered || ! restatify_should_inject_lightstart_template_chrome()) {
        return;
    }

    $markup = restatify_render_lightstart_footer_markup();
    if ($markup === '') {
        return;
    }

    echo $markup;
    $footer_rendered = true;
}

function restatify_disable_chat_assets_for_maintenance(): void {
    if (! restatify_use_maintenance_layout()) {
        return;
    }

    wp_dequeue_style('restatify-booking-assistant-css');
    wp_dequeue_style('restatify-multi-chat-overlay-css');
    wp_dequeue_script('restatify-booking-assistant-js');
    wp_dequeue_script('restatify-multi-chat-overlay-js');
}
add_action('wp_enqueue_scripts', 'restatify_disable_chat_assets_for_maintenance', 999);

function restatify_hide_chat_markup_for_maintenance(): void {
    if (! restatify_use_maintenance_layout()) {
        return;
    }

    $selectors = [
        '.restatify-booking',
        '.restatify-mco',
    ];

    if (! restatify_should_show_cookie_banner_in_maintenance()) {
        $selectors[] = '#restatify-cookie-banner';
        $selectors[] = '#restatify-cookie-backdrop';
    }

    echo '<style id="restatify-maintenance-hide-chat">'
        . implode(',', $selectors)
        . '{display:none !important;}'
        . '</style>';
}
add_action('wp_head', 'restatify_hide_chat_markup_for_maintenance', 999);

function restatify_force_bright_theme_for_maintenance(): void {
    if (! restatify_should_inject_lightstart_chrome()) {
        return;
    }

    echo '<style id="restatify-maintenance-bright-theme">'
        . ':root,'
        . 'html[data-rs-theme="dark"],' 
        . 'html[data-rs-theme="light"]{'
        . '--rs-color-primary:#ff6b00 !important;'
        . '--rs-color-primary-hover:#a84700 !important;'
        . '--rs-color-secondary:#00c2ff !important;'
        . '--rs-color-secondary-hover:#0080a8 !important;'
        . '--rs-color-background:#f8fafc !important;'
        . '--rs-color-text:#0b1221 !important;'
        . '--rs-color-contrast:#ffffff !important;'
        . 'color-scheme:light !important;'
        . '}'
        . 'body{background:#f8fafc !important;color:#0b1221 !important;}'
        . '</style>';

    echo '<script id="restatify-maintenance-bright-theme-script">'
        . 'document.documentElement.setAttribute("data-rs-theme","light");'
        . '</script>';
}
add_action('wp_head', 'restatify_force_bright_theme_for_maintenance', 8);

// Legacy maintenance template hook.
add_action('wpmm_after_body', 'restatify_output_lightstart_minimal_header', 5);
// New-look template hook.
add_action('wp_body_open', 'restatify_output_lightstart_minimal_header', 5);
// Shared footer hook used by LightStart templates.
add_action('wpmm_footer', 'restatify_output_lightstart_minimal_footer', 5);