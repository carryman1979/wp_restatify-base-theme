<?php
/**
 * Optional Polylang integration.
 */

function restatify_register_polylang_strings() {
    if (!function_exists('pll_register_string')) {
        return;
    }

    $cta_label = trim((string) get_theme_mod('restatify_cta_button_label', __('Start now', 'restatify-base')));
    $cta_label = $cta_label !== '' ? $cta_label : __('Start now', 'restatify-base');

    pll_register_string('Header CTA Label', $cta_label, 'Restatify Theme');
}
add_action('init', 'restatify_register_polylang_strings');
