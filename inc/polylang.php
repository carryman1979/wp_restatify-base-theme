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

    foreach ($strings as $name => $value) {
        if ($value !== '') {
            pll_register_string($name, $value, 'Restatify Theme');
        }
    }
}
add_action('init', 'restatify_register_polylang_strings');
