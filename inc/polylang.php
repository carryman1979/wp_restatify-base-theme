<?php
/**
 * Optional Polylang integration.
 */

function restatify_register_polylang_strings() {
    if (!function_exists('pll_register_string')) {
        return;
    }

    $strings = [
        'Header CTA Label' => trim((string) get_theme_mod('restatify_cta_button_label', __('Start now', 'restatify-base'))),
        'Footer Slogan' => trim((string) get_theme_mod('restatify_footer_title', __('Strategie, die mit Ihrem Unternehmen mitwaechst.', 'restatify-base'))),
        'Footer Description' => trim((string) get_theme_mod('restatify_footer_description', __('Wir unterstuetzen Sie bei Positionierung, Wachstum und Umsetzung - strukturiert, messbar und hands-on.', 'restatify-base'))),
        'Footer vCard Text' => trim((string) get_theme_mod('restatify_footer_vcard_text', __('vCard Gruender', 'restatify-base'))),
        'Footer vCard URL' => trim((string) get_theme_mod('restatify_footer_vcard_url', '')),
        'Footer Badge 1 URL' => trim((string) get_theme_mod('restatify_footer_trust_badge_1_url', '')),
        'Footer Badge 2 URL' => trim((string) get_theme_mod('restatify_footer_trust_badge_2_url', '')),
        'Footer Badge 3 URL' => trim((string) get_theme_mod('restatify_footer_trust_badge_3_url', '')),
    ];

    $footer_column_defaults = restatify_get_footer_column_defaults();

    for ($column_index = 1; $column_index <= 3; $column_index++) {
        $default_title = (string) ($footer_column_defaults[$column_index]['title'] ?? '');
        $strings['Footer Column ' . $column_index . ' Title'] = trim((string) get_theme_mod('restatify_footer_col_' . $column_index . '_title', $default_title));

        for ($link_index = 1; $link_index <= 4; $link_index++) {
            $default_link = (array) ($footer_column_defaults[$column_index]['links'][$link_index - 1] ?? []);
            $default_link_title = (string) ($default_link['title'] ?? '');
            $default_link_url = (string) ($default_link['url'] ?? '');

            $strings['Footer Column ' . $column_index . ' Link ' . $link_index . ' Title'] = trim((string) get_theme_mod('restatify_footer_col_' . $column_index . '_link_' . $link_index . '_title', $default_link_title));
            $strings['Footer Column ' . $column_index . ' Link ' . $link_index . ' URL'] = trim((string) get_theme_mod('restatify_footer_col_' . $column_index . '_link_' . $link_index . '_url', $default_link_url));
        }
    }

    foreach ($strings as $name => $value) {
        if ($value !== '') {
            pll_register_string($name, $value, 'Restatify Theme');
        }
    }
}
add_action('init', 'restatify_register_polylang_strings');
