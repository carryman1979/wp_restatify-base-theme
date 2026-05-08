<?php
/**
 * Theme setup and supports.
 */

function restatify_setup() {
    // Editor Styles & Wide Align
    add_theme_support('editor-styles');
    add_editor_style([
        'assets/bootstrap/css/bootstrap.min.css',
        'assets/theme/css/style.css',
        'assets/mobirise/css/mbr-additional.css',
        'https://fonts.googleapis.com/css?family=Science+Gothic:100,200,300,400,500,600,700,800,900&display=swap'
    ]);
    add_theme_support('align-wide');

    // Logo Support
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 350,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Menü-Registrierung (Zentralisiert)
    register_nav_menus([
        'primary_menu' => 'Header Hauptmenü',
        'cta_button_menu' => 'Header Aktions-Button (Start Now Ersatz)',
        'header_social_menu' => 'Header Social Links'
    ]);
}
add_action('after_setup_theme', 'restatify_setup');

/**
 * Show admin notice when no primary header menu is assigned.
 */
function restatify_primary_menu_admin_notice() {
    if (! is_admin() || ! current_user_can('manage_options')) {
        return;
    }

    if (wp_doing_ajax()) {
        return;
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ($screen && in_array($screen->base, ['update', 'plugins'], true)) {
        return;
    }

    $locations = get_nav_menu_locations();
    $menu_id = isset($locations['primary_menu']) ? (int) $locations['primary_menu'] : 0;
    if ($menu_id > 0 && wp_get_nav_menu_object($menu_id) instanceof WP_Term) {
        return;
    }

    $menu_url = admin_url('nav-menus.php?action=locations');

    echo '<div class="notice notice-warning is-dismissible">';
    echo '<p>';
    echo wp_kses_post(
        sprintf(
            __('Dem Header-Hauptmenue ist noch kein WordPress-Menue zugewiesen. Bitte ordne unter <a href="%s">Design > Menues > Menuepositionen</a> ein Menue der Position "Header Hauptmenue" zu.', 'restatify-base'),
            esc_url($menu_url)
        )
    );
    echo '</p>';
    echo '</div>';
}
add_action('admin_notices', 'restatify_primary_menu_admin_notice');

/**
 * Allow SVG uploads for administrators.
 */
function restatify_allow_svg_uploads($mimes) {
    if (! current_user_can('manage_options')) {
        return $mimes;
    }

    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;
}
add_filter('upload_mimes', 'restatify_allow_svg_uploads');

/**
 * Sanitize SVG markup by removing unsafe elements/attributes.
 */
function restatify_sanitize_svg_markup($svg_markup) {
    if (stripos($svg_markup, '<!ENTITY') !== false) {
        return new WP_Error('restatify_svg_entity_not_allowed', __('SVG contains unsafe ENTITY declarations.', 'restatify-base'));
    }

    if (stripos($svg_markup, '<!DOCTYPE') !== false) {
        return new WP_Error('restatify_svg_doctype_not_allowed', __('SVG contains unsafe DOCTYPE declarations.', 'restatify-base'));
    }

    if (! class_exists('DOMDocument')) {
        // Fallback hard block for the most dangerous patterns when DOM extension is unavailable.
        if (preg_match('/<\s*script|on[a-z]+\s*=|javascript\s*:/i', $svg_markup)) {
            return new WP_Error('restatify_svg_unsafe_content', __('SVG contains unsafe script content.', 'restatify-base'));
        }

        return $svg_markup;
    }

    $allowed_tags = [
        'svg', 'g', 'path', 'circle', 'rect', 'ellipse', 'line', 'polyline', 'polygon',
        'defs', 'lineargradient', 'radialgradient', 'stop', 'clippath', 'mask', 'pattern',
        'title', 'desc', 'symbol', 'use', 'text', 'tspan', 'style',
    ];

    $allowed_attrs = [
        'id', 'class', 'x', 'y', 'x1', 'x2', 'y1', 'y2', 'cx', 'cy', 'r', 'rx', 'ry',
        'width', 'height', 'viewbox', 'd', 'points', 'fill', 'fill-opacity', 'stroke',
        'stroke-width', 'stroke-linecap', 'stroke-linejoin', 'stroke-miterlimit',
        'stroke-dasharray', 'stroke-dashoffset', 'stroke-opacity', 'opacity', 'transform',
        'preserveaspectratio', 'xmlns', 'xmlns:xlink', 'version', 'role', 'aria-hidden',
        'focusable', 'gradientunits', 'gradienttransform', 'offset', 'stop-color',
        'stop-opacity', 'clip-path', 'cliprule', 'mask', 'maskunits', 'maskcontentunits',
        'patternunits', 'patterncontentunits', 'patterntransform', 'href', 'xlink:href',
        'text-anchor', 'font-size', 'font-family', 'font-weight', 'letter-spacing',
        'dominant-baseline', 'style',
    ];

    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $loaded = $dom->loadXML($svg_markup, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
    if (! $loaded || ! $dom->documentElement || strtolower($dom->documentElement->nodeName) !== 'svg') {
        libxml_clear_errors();

        return new WP_Error('restatify_svg_invalid_xml', __('SVG file is invalid or malformed.', 'restatify-base'));
    }

    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query('//*');

    if ($nodes instanceof DOMNodeList) {
        $to_remove = [];

        foreach ($nodes as $node) {
            $tag_name = strtolower($node->nodeName);
            if (! in_array($tag_name, $allowed_tags, true)) {
                $to_remove[] = $node;
                continue;
            }

            if (! $node->hasAttributes()) {
                continue;
            }

            $attributes = [];
            foreach ($node->attributes as $attribute) {
                $attributes[] = $attribute;
            }

            foreach ($attributes as $attribute) {
                $attr_name = strtolower($attribute->nodeName);
                $attr_value = trim((string) $attribute->nodeValue);

                if (! in_array($attr_name, $allowed_attrs, true)) {
                    if ($node instanceof DOMElement) {
                        $node->removeAttribute($attribute->nodeName);
                    }
                    continue;
                }

                if (strpos($attr_name, 'on') === 0) {
                    if ($node instanceof DOMElement) {
                        $node->removeAttribute($attribute->nodeName);
                    }
                    continue;
                }

                if (($attr_name === 'href' || $attr_name === 'xlink:href') && preg_match('/^\s*(javascript:|data:\s*text\/html)/i', $attr_value)) {
                    if ($node instanceof DOMElement) {
                        $node->removeAttribute($attribute->nodeName);
                    }
                    continue;
                }

                if ($attr_name === 'style' && preg_match('/expression\s*\(|javascript\s*:/i', $attr_value)) {
                    if ($node instanceof DOMElement) {
                        $node->removeAttribute($attribute->nodeName);
                    }
                }
            }
        }

        foreach ($to_remove as $node) {
            if ($node->parentNode) {
                $node->parentNode->removeChild($node);
            }
        }
    }

    $sanitized_svg = $dom->saveXML($dom->documentElement);
    libxml_clear_errors();

    if (! is_string($sanitized_svg) || $sanitized_svg === '') {
        return new WP_Error('restatify_svg_sanitize_failed', __('SVG could not be sanitized.', 'restatify-base'));
    }

    return $sanitized_svg;
}

/**
 * Sanitize uploaded SVG/SVGZ files in temp upload location.
 */
function restatify_sanitize_svg_file($tmp_name, $extension) {
    if (! is_readable($tmp_name) || ! is_writable($tmp_name)) {
        return new WP_Error('restatify_svg_file_unreadable', __('SVG upload file is not readable/writable.', 'restatify-base'));
    }

    $raw_contents = file_get_contents($tmp_name);
    if ($raw_contents === false) {
        return new WP_Error('restatify_svg_file_read_failed', __('SVG upload file could not be read.', 'restatify-base'));
    }

    $svg_markup = $raw_contents;
    $is_svgz = ($extension === 'svgz');

    if ($is_svgz) {
        if (! function_exists('gzdecode') || ! function_exists('gzencode')) {
            return new WP_Error('restatify_svgz_not_supported', __('SVGZ upload requires gzip support in PHP.', 'restatify-base'));
        }

        $decoded = gzdecode($raw_contents);
        if ($decoded === false) {
            return new WP_Error('restatify_svgz_decode_failed', __('SVGZ file could not be decoded.', 'restatify-base'));
        }

        $svg_markup = $decoded;
    }

    $sanitized = restatify_sanitize_svg_markup($svg_markup);
    if (is_wp_error($sanitized)) {
        return $sanitized;
    }

    $content_to_write = $is_svgz ? gzencode($sanitized, 9) : $sanitized;
    if ($content_to_write === false) {
        return new WP_Error('restatify_svg_write_failed', __('SVG upload file could not be written.', 'restatify-base'));
    }

    $written = file_put_contents($tmp_name, $content_to_write);
    if ($written === false) {
        return new WP_Error('restatify_svg_write_failed', __('SVG upload file could not be written.', 'restatify-base'));
    }

    return true;
}

/**
 * Apply SVG sanitization for normal uploads and sideload imports.
 */
function restatify_svg_upload_prefilter($file) {
    if (! current_user_can('manage_options')) {
        return $file;
    }

    $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
    if (! in_array($extension, ['svg', 'svgz'], true)) {
        return $file;
    }

    $tmp_name = $file['tmp_name'] ?? '';
    if (! is_string($tmp_name) || $tmp_name === '' || ! file_exists($tmp_name)) {
        return $file;
    }

    $sanitized = restatify_sanitize_svg_file($tmp_name, $extension);
    if (is_wp_error($sanitized)) {
        $file['error'] = $sanitized->get_error_message();
    }

    return $file;
}
add_filter('wp_handle_upload_prefilter', 'restatify_svg_upload_prefilter');
add_filter('wp_handle_sideload_prefilter', 'restatify_svg_upload_prefilter');

/**
 * Validate SVG MIME/type mapping during upload checks.
 */
function restatify_fix_svg_filetype_and_ext($data, $file, $filename, $mimes) {
    if (! current_user_can('manage_options')) {
        return $data;
    }

    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (! in_array($extension, ['svg', 'svgz'], true)) {
        return $data;
    }

    $filetype = wp_check_filetype($filename, $mimes);

    if (! empty($filetype['ext']) && ! empty($filetype['type'])) {
        $data['ext'] = $filetype['ext'];
        $data['type'] = $filetype['type'];
    }

    return $data;
}
add_filter('wp_check_filetype_and_ext', 'restatify_fix_svg_filetype_and_ext', 10, 4);
