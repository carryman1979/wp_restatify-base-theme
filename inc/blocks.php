<?php
// Block-Registrierung für das Theme
add_action('init', function() {
    $hero_block_path = __DIR__ . '/../build/hero';

    if ( file_exists( $hero_block_path . '/block.json' ) ) {
        register_block_type( $hero_block_path );

        if ( function_exists( 'wp_set_script_translations' ) ) {
            wp_set_script_translations(
                'restatify-hero-editor-script',
                'restatify-base',
                get_template_directory() . '/languages'
            );
        }
    }
});

function restatify_hero_default_background_variable() {
    $default_background_url = esc_url(get_template_directory_uri() . '/assets/images/background1.webp');
    $inline_css = ':root{--rs-hero-default-background:url("' . $default_background_url . '");}';

    foreach (['restatify-hero-style', 'restatify-hero-editor-style'] as $style_handle) {
        if (wp_style_is($style_handle, 'registered') || wp_style_is($style_handle, 'enqueued')) {
            wp_add_inline_style($style_handle, $inline_css);
        }
    }
}

add_action('enqueue_block_assets', 'restatify_hero_default_background_variable');
add_action('enqueue_block_editor_assets', 'restatify_hero_default_background_variable');
