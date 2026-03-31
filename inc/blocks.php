<?php
// Register all built custom blocks from /build/*.
add_action( 'init', function() {
    $build_root = __DIR__ . '/../build';

    if ( ! is_dir( $build_root ) ) {
        return;
    }

    $block_json_files = glob( $build_root . '/*/block.json' );

    if ( ! is_array( $block_json_files ) ) {
        return;
    }

    foreach ( $block_json_files as $block_json_file ) {
        $block_path = dirname( $block_json_file );
        register_block_type( $block_path );
    }
} );

function restatify_block_default_background_variables() {
    $default_background_url = esc_url( get_template_directory_uri() . '/assets/images/background1.webp' );
    $inline_css            = ':root{--rs-hero-default-background:url("' . $default_background_url . '");--rs-services-default-background:url("' . $default_background_url . '");--rs-studies-default-background:url("' . $default_background_url . '");--rs-testimonials-default-background:url("' . $default_background_url . '");--rs-mission-default-background:url("' . $default_background_url . '");--rs-metrics-default-background:url("' . $default_background_url . '");--rs-insight-default-background:url("' . $default_background_url . '");--rs-gallery-default-background:url("' . $default_background_url . '");--rs-faq-default-background:url("' . $default_background_url . '");--rs-pricing-default-background:url("' . $default_background_url . '");--rs-contact-default-background:url("' . $default_background_url . '");--rs-clients-default-background:url("' . $default_background_url . '");--rs-oracle-default-background:url("' . $default_background_url . '");}';

    foreach ( [
        'restatify-hero-style',
        'restatify-hero-editor-style',
        'restatify-services-style',
        'restatify-services-editor-style',
        'restatify-studies-style',
        'restatify-studies-editor-style',
        'restatify-testimonials-style',
        'restatify-testimonials-editor-style',
        'restatify-mission-style',
        'restatify-mission-editor-style',
        'restatify-metrics-style',
        'restatify-metrics-editor-style',
        'restatify-insight-style',
        'restatify-insight-editor-style',
        'restatify-gallery-style',
        'restatify-gallery-editor-style',
        'restatify-faq-style',
        'restatify-faq-editor-style',
        'restatify-pricing-style',
        'restatify-pricing-editor-style',
        'restatify-contact-style',
        'restatify-contact-editor-style',
        'restatify-clients-style',
        'restatify-clients-editor-style',
        'restatify-oracle-style',
        'restatify-oracle-editor-style',
    ] as $style_handle ) {
        if ( wp_style_is( $style_handle, 'registered' ) || wp_style_is( $style_handle, 'enqueued' ) ) {
            wp_add_inline_style( $style_handle, $inline_css );
        }
    }
}

add_action( 'enqueue_block_assets', 'restatify_block_default_background_variables' );
add_action( 'enqueue_block_editor_assets', 'restatify_block_default_background_variables' );
