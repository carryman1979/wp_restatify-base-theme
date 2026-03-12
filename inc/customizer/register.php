<?php
/**
 * Customizer settings and controls.
 */

function restatify_customize_register($wp_customize) {
    $wp_customize->add_section('restatify_typography', [
        'title'       => __('Typography', 'restatify-base'),
        'priority'    => 31,
        'description' => __('Control website fonts and sizes. Reminder: mobile fields inherit desktop values when left empty.', 'restatify-base'),
    ]);

    $wp_customize->add_setting('restatify_font_body', [
        'default'           => 'var(--wp--preset--font-family--ubuntu)',
        'sanitize_callback' => 'restatify_sanitize_font_choice',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_font_body', [
        'label'   => __('Body font', 'restatify-base'),
        'section' => 'restatify_typography',
        'type'    => 'select',
        'choices' => restatify_get_font_choices(),
    ]);

    $wp_customize->add_setting('restatify_font_headings', [
        'default'           => 'var(--wp--preset--font-family--science-gothic)',
        'sanitize_callback' => 'restatify_sanitize_font_choice',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_font_headings', [
        'label'   => __('Headings font (H1-H6)', 'restatify-base'),
        'section' => 'restatify_typography',
        'type'    => 'select',
        'choices' => restatify_get_font_choices(),
    ]);

    $wp_customize->add_setting('restatify_font_paragraph', [
        'default'           => 'var(--wp--preset--font-family--ubuntu)',
        'sanitize_callback' => 'restatify_sanitize_font_choice',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_font_paragraph', [
        'label'   => __('Paragraph font (p, li)', 'restatify-base'),
        'section' => 'restatify_typography',
        'type'    => 'select',
        'choices' => restatify_get_font_choices(),
    ]);

    $wp_customize->add_setting('restatify_font_links', [
        'default'           => 'var(--wp--preset--font-family--ubuntu)',
        'sanitize_callback' => 'restatify_sanitize_font_choice',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_font_links', [
        'label'   => __('Links & buttons font', 'restatify-base'),
        'section' => 'restatify_typography',
        'type'    => 'select',
        'choices' => restatify_get_font_choices(),
    ]);

    $wp_customize->add_setting('restatify_size_body', [
        'default'           => '1rem',
        'sanitize_callback' => 'restatify_sanitize_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_body', [
        'label'       => __('Body size', 'restatify-base'),
        'description' => __('Examples: 16px, 1rem, 1.05rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h1', [
        'default'           => '3.2rem',
        'sanitize_callback' => 'restatify_sanitize_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h1', [
        'label'       => __('H1 size', 'restatify-base'),
        'description' => __('Examples: 52px, 3.2rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h2', [
        'default'           => '2.4rem',
        'sanitize_callback' => 'restatify_sanitize_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h2', [
        'label'       => __('H2 size', 'restatify-base'),
        'description' => __('Examples: 38px, 2.4rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h3', [
        'default'           => '1.8rem',
        'sanitize_callback' => 'restatify_sanitize_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h3', [
        'label'       => __('H3 size', 'restatify-base'),
        'description' => __('Examples: 30px, 1.8rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_links', [
        'default'           => '1rem',
        'sanitize_callback' => 'restatify_sanitize_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_links', [
        'label'       => __('Links & buttons size', 'restatify-base'),
        'description' => __('Examples: 16px, 1rem, 0.95rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_line_height_base', [
        'default'           => '1.5',
        'sanitize_callback' => 'restatify_sanitize_line_height',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_line_height_base', [
        'label'       => __('Base line-height', 'restatify-base'),
        'description' => __('Number only, e.g. 1.45, 1.5, 1.6', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_body_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_body_mobile', [
        'label'       => __('Body size (mobile, empty = desktop)', 'restatify-base'),
        'description' => __('Optional override. Leave empty to automatically use desktop Body size.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h1_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h1_mobile', [
        'label'       => __('H1 size (mobile, empty = desktop)', 'restatify-base'),
        'description' => __('Optional override. Leave empty to automatically use desktop H1 size.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h2_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h2_mobile', [
        'label'       => __('H2 size (mobile, empty = desktop)', 'restatify-base'),
        'description' => __('Optional override. Leave empty to automatically use desktop H2 size.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h3_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h3_mobile', [
        'label'       => __('H3 size (mobile, empty = desktop)', 'restatify-base'),
        'description' => __('Optional override. Leave empty to automatically use desktop H3 size.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_links_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_links_mobile', [
        'label'       => __('Links & buttons size (mobile, empty = desktop)', 'restatify-base'),
        'description' => __('Optional override. Leave empty to automatically use desktop Links & buttons size.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_line_height_base_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_line_height',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_line_height_base_mobile', [
        'label'       => __('Base line-height (mobile, empty = desktop)', 'restatify-base'),
        'description' => __('Optional override. Leave empty to automatically use desktop Base line-height.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_dark_logo', [
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_dark_logo', [
        'label'      => __('Dark Mode Logo', 'restatify-base'),
        'description'=> __('Used automatically when the visitor prefers dark mode.', 'restatify-base'),
        'section'    => 'title_tagline',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_setting('restatify_dark_site_icon', [
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_dark_site_icon', [
        'label'       => __('Dark Mode Site Icon', 'restatify-base'),
        'description' => __('Used as browser tab icon when the visitor prefers dark mode.', 'restatify-base'),
        'section'     => 'title_tagline',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('restatify_dark_mask_icon', [
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_dark_mask_icon', [
        'label'       => __('Dark Mask Icon (SVG)', 'restatify-base'),
        'description' => __('Used for Safari pinned tabs in dark mode. SVG recommended.', 'restatify-base'),
        'section'     => 'title_tagline',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('restatify_cta_button_label', [
        'default'           => __('Start now', 'restatify-base'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('restatify_cta_button_label', [
        'label'       => __('CTA Button Label', 'restatify-base'),
        'description' => __('Text shown on the header action button.', 'restatify-base'),
        'section'     => 'title_tagline',
        'type'        => 'text',
    ]);
}
add_action('customize_register', 'restatify_customize_register');
