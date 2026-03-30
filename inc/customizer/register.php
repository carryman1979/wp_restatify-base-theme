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

    $wp_customize->add_section('restatify_footer', [
        'title'       => __('Footer Content', 'restatify-base'),
        'priority'    => 32,
        'description' => __('Manage dynamic footer text, quick contact links, social URLs, trust badges and vCard.', 'restatify-base'),
    ]);

    $wp_customize->add_setting('restatify_footer_title', [
        'default'           => __('Strategie, die mit Ihrem Unternehmen mitwaechst.', 'restatify-base'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_title', [
        'label'       => __('Footer slogan', 'restatify-base'),
        'description' => __('Main headline in the footer title area.', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_footer_description', [
        'default'           => __('Wir unterstuetzen Sie bei Positionierung, Wachstum und Umsetzung - strukturiert, messbar und hands-on.', 'restatify-base'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_description', [
        'label'       => __('Footer description', 'restatify-base'),
        'description' => __('Descriptive paragraph below the footer slogan.', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_setting('restatify_footer_phone', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_phone',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_phone', [
        'label'       => __('Quick contact phone', 'restatify-base'),
        'description' => __('Example: +49 123 456789', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_footer_email', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_email', [
        'label'       => __('Quick contact email', 'restatify-base'),
        'description' => __('Example: hello@example.com', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'email',
    ]);

    $wp_customize->add_setting('restatify_footer_fax', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_phone',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_fax', [
        'label'       => __('Company fax', 'restatify-base'),
        'description' => __('Example: +49 123 456790', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_footer_social_linkedin', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_url',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_social_linkedin', [
        'label'       => __('LinkedIn URL', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_footer_social_xing', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_url',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_social_xing', [
        'label'       => __('Xing URL', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_footer_social_facebook', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_url',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_social_facebook', [
        'label'       => __('Facebook URL', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_footer_vcard_url', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_url',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_vcard_url', [
        'label'       => __('vCard URL', 'restatify-base'),
        'description' => __('Paste the URL to your downloadable vCard.', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_footer_vcard_text', [
        'default'           => __('vCard Gruender', 'restatify-base'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_vcard_text', [
        'label'       => __('vCard link text', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_footer_trust_badge_1', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_footer_trust_badge_1', [
        'label'       => __('Trust badge 1', 'restatify-base'),
        'section'     => 'restatify_footer',
        'settings'    => 'restatify_footer_trust_badge_1',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('restatify_footer_trust_badge_1_url', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_url',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_trust_badge_1_url', [
        'label'       => __('Trust badge 1 link URL (optional)', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_footer_trust_badge_2', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_footer_trust_badge_2', [
        'label'       => __('Trust badge 2', 'restatify-base'),
        'section'     => 'restatify_footer',
        'settings'    => 'restatify_footer_trust_badge_2',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('restatify_footer_trust_badge_2_url', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_url',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_trust_badge_2_url', [
        'label'       => __('Trust badge 2 link URL (optional)', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_footer_trust_badge_3', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_footer_trust_badge_3', [
        'label'       => __('Trust badge 3', 'restatify-base'),
        'section'     => 'restatify_footer',
        'settings'    => 'restatify_footer_trust_badge_3',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('restatify_footer_trust_badge_3_url', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_url',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_trust_badge_3_url', [
        'label'       => __('Trust badge 3 link URL (optional)', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);
    // ── Hero Section ────────────────────────────────────────────────────────
    $wp_customize->add_section( 'restatify_hero', [
        'title'       => __( 'Hero Section', 'restatify-base' ),
        'priority'    => 30,
        'description' => __( 'Texts and button links for the hero block on the homepage.', 'restatify-base' ),
    ] );

    // Parallax background toggle
    $wp_customize->add_setting( 'restatify_hero_parallax', [
        'default'           => false,
        'sanitize_callback' => 'restatify_sanitize_checkbox',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_parallax', [
        'label'       => __( 'Parallax-Effekt für Hintergrundbild aktivieren', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'checkbox',
    ] );

    // Fullscreen toggle
    $wp_customize->add_setting( 'restatify_hero_fullscreen', [
        'default'           => false,
        'sanitize_callback' => 'restatify_sanitize_checkbox',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_fullscreen', [
        'label'       => __( 'Hero-Block immer Fullscreen anzeigen (100vw/100vh)', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'checkbox',
    ] );

    $wp_customize->add_setting( 'restatify_hero_tagline', [
        'default'           => __( 'Strategic guidance turning complexity into sustainable business growth', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_tagline', [
        'label'       => __( 'Tagline (small text above heading)', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_heading', [
        'default'           => __( 'Business Consultant', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_heading', [
        'label'       => __( 'Main heading (H2)', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_text', [
        'default'           => __( 'A Business Consultant partners with organizations to clarify vision, solve complex challenges, and unlock new opportunities.', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_text', [
        'label'       => __( 'Body text below heading', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'textarea',
    ] );

    $wp_customize->add_setting( 'restatify_hero_btn1_label', [
        'default'           => __( 'Start now', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_btn1_label', [
        'label'       => __( 'Primary button label', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_btn1_url', [
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_btn1_url', [
        'label'       => __( 'Primary button URL', 'restatify-base' ),
        'description' => __( 'Internal anchor (e.g. #pricing01-k) or full URL.', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_btn2_label', [
        'default'           => __( 'Contact now', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_btn2_label', [
        'label'       => __( 'Secondary button label', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_btn2_url', [
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_btn2_url', [
        'label'       => __( 'Secondary button URL', 'restatify-base' ),
        'description' => __( 'Internal anchor (e.g. #contacts01-c) or full URL.', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );
}
add_action('customize_register', 'restatify_customize_register');
