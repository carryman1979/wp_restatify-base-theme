<?php
/**
 * Customizer settings and controls.
 */

function restatify_customize_register($wp_customize) {
    $wp_customize->add_section('restatify_typography', [
        'title'       => __('Typografie', 'restatify-base'),
        'priority'    => 31,
        'description' => __('Steuere Schriftarten und Größen der Website. Hinweis: Mobile Felder übernehmen Desktop-Werte, wenn sie leer bleiben.', 'restatify-base'),
    ]);

    $wp_customize->add_setting('restatify_font_body', [
        'default'           => 'var(--wp--preset--font-family--ubuntu)',
        'sanitize_callback' => 'restatify_sanitize_font_choice',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_font_body', [
        'label'   => __('Fließtext-Schrift', 'restatify-base'),
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
        'label'   => __('Überschriften-Schrift (H1-H6)', 'restatify-base'),
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
        'label'   => __('Absatz-Schrift (p, li)', 'restatify-base'),
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
        'label'   => __('Links- und Button-Schrift', 'restatify-base'),
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
        'label'       => __('Fließtext-Größe', 'restatify-base'),
        'description' => __('Beispiele: 16px, 1rem, 1.05rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h1', [
        'default'           => '3.2rem',
        'sanitize_callback' => 'restatify_sanitize_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h1', [
        'label'       => __('H1-Größe', 'restatify-base'),
        'description' => __('Beispiele: 52px, 3.2rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h2', [
        'default'           => '2.4rem',
        'sanitize_callback' => 'restatify_sanitize_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h2', [
        'label'       => __('H2-Größe', 'restatify-base'),
        'description' => __('Beispiele: 38px, 2.4rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h3', [
        'default'           => '1.8rem',
        'sanitize_callback' => 'restatify_sanitize_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h3', [
        'label'       => __('H3-Größe', 'restatify-base'),
        'description' => __('Beispiele: 30px, 1.8rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_links', [
        'default'           => '1rem',
        'sanitize_callback' => 'restatify_sanitize_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_links', [
        'label'       => __('Links- und Button-Größe', 'restatify-base'),
        'description' => __('Beispiele: 16px, 1rem, 0.95rem', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_line_height_base', [
        'default'           => '1.5',
        'sanitize_callback' => 'restatify_sanitize_line_height',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_line_height_base', [
        'label'       => __('Basis-Zeilenhoehe', 'restatify-base'),
        'description' => __('Nur Zahl, z.B. 1.45, 1.5, 1.6', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_body_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_body_mobile', [
        'label'       => __('Fließtext-Größe (mobil, leer = Desktop)', 'restatify-base'),
        'description' => __('Optionale Überschreibung. Leer lassen, um automatisch die Desktop-Fließtext-Größe zu verwenden.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h1_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h1_mobile', [
        'label'       => __('H1-Größe (mobil, leer = Desktop)', 'restatify-base'),
        'description' => __('Optionale Überschreibung. Leer lassen, um automatisch die Desktop-H1-Größe zu verwenden.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h2_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h2_mobile', [
        'label'       => __('H2-Größe (mobil, leer = Desktop)', 'restatify-base'),
        'description' => __('Optionale Überschreibung. Leer lassen, um automatisch die Desktop-H2-Größe zu verwenden.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_h3_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_h3_mobile', [
        'label'       => __('H3-Größe (mobil, leer = Desktop)', 'restatify-base'),
        'description' => __('Optionale Überschreibung. Leer lassen, um automatisch die Desktop-H3-Größe zu verwenden.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_size_links_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_font_size',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_size_links_mobile', [
        'label'       => __('Links- und Button-Größe (mobil, leer = Desktop)', 'restatify-base'),
        'description' => __('Optionale Überschreibung. Leer lassen, um automatisch die Desktop-Links- und Button-Größe zu verwenden.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_line_height_base_mobile', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_optional_line_height',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_line_height_base_mobile', [
        'label'       => __('Basis-Zeilenhoehe (mobil, leer = Desktop)', 'restatify-base'),
        'description' => __('Optionale Überschreibung. Leer lassen, um automatisch die Desktop-Basis-Zeilenhoehe zu verwenden.', 'restatify-base'),
        'section'     => 'restatify_typography',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_dark_logo', [
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_dark_logo', [
        'label'      => __('Dark-Mode-Logo', 'restatify-base'),
        'description'=> __('Wird automatisch verwendet, wenn Besucher den Dunkelmodus bevorzugen.', 'restatify-base'),
        'section'    => 'title_tagline',
        'mime_type'  => 'image',
    ]));

    $wp_customize->add_setting('restatify_dark_site_icon', [
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_dark_site_icon', [
        'label'       => __('Dark-Mode-Site-Icon', 'restatify-base'),
        'description' => __('Wird als Browser-Tab-Icon verwendet, wenn Besucher den Dunkelmodus bevorzugen.', 'restatify-base'),
        'section'     => 'title_tagline',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('restatify_dark_mask_icon', [
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_dark_mask_icon', [
        'label'       => __('Dunkles Masken-Icon (SVG)', 'restatify-base'),
        'description' => __('Wird für Safari angeheftete Tabs im Dunkelmodus verwendet. SVG empfohlen.', 'restatify-base'),
        'section'     => 'title_tagline',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('restatify_cta_button_label', [
        'default'           => __('Jetzt starten', 'restatify-base'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);

    $wp_customize->add_control('restatify_cta_button_label', [
        'label'       => __('CTA-Button-Beschriftung', 'restatify-base'),
        'description' => __('Text, der auf dem Aktionsbutton im Header angezeigt wird.', 'restatify-base'),
        'section'     => 'title_tagline',
        'type'        => 'text',
    ]);

    $wp_customize->add_section('restatify_footer_core', [
        'title'       => __('Footer Kernbereich (empfohlen)', 'restatify-base'),
        'priority'    => 32,
        'description' => __('Hier starten: Slogan und primäre Kontaktinformationen, die im Footer prominent verwendet werden.', 'restatify-base'),
    ]);

    $wp_customize->add_section('restatify_footer', [
        'title'       => __('Footer Experteneinstellungen (optional)', 'restatify-base'),
        'priority'    => 33,
        'description' => __('Optionale Social-Links, Trust-Badges und vCard-Einstellungen.', 'restatify-base'),
    ]);

    $wp_customize->add_setting('restatify_footer_title', [
        'default'           => __('Strategie, die mit Ihrem Unternehmen mitwächst.', 'restatify-base'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_title', [
        'label'       => __('Footer-Slogan', 'restatify-base'),
        'description' => __('Hauptüberschrift im Footer-Titelbereich.', 'restatify-base'),
        'section'     => 'restatify_footer_core',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_footer_description', [
        'default'           => __('Wir unterstützen Sie bei Positionierung, Wachstum und Umsetzung - strukturiert, messbar und hands-on.', 'restatify-base'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_description', [
        'label'       => __('Footer-Beschreibung', 'restatify-base'),
        'description' => __('Beschreibender Absatz unter dem Footer-Slogan.', 'restatify-base'),
        'section'     => 'restatify_footer_core',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_setting('restatify_footer_phone', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_phone',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_phone', [
        'label'       => __('Telefon für Schnellkontakt', 'restatify-base'),
        'description' => __('Beispiel: +49 123 456789', 'restatify-base'),
        'section'     => 'restatify_footer_core',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_footer_email', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_email', [
        'label'       => __('E-Mail für Schnellkontakt', 'restatify-base'),
        'description' => __('Beispiel: kontakt@example.com', 'restatify-base'),
        'section'     => 'restatify_footer_core',
        'type'        => 'email',
        'input_attrs' => [
            'required' => 'required',
            'placeholder' => 'kontakt@example.com',
        ],
    ]);

    $wp_customize->add_setting('restatify_footer_fax', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_phone',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_fax', [
        'label'       => __('Fax', 'restatify-base'),
        'description' => __('Beispiel: +49 123 456790', 'restatify-base'),
        'section'     => 'restatify_footer_core',
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
        'description' => __('Fuege die URL zu deiner herunterladbaren vCard ein.', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_footer_vcard_text', [
        'default'           => __('vCard Gruender', 'restatify-base'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_vcard_text', [
        'label'       => __('vCard-Linktext', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_footer_trust_badge_1', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_footer_trust_badge_1', [
        'label'       => __('Trust-Badge 1', 'restatify-base'),
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
        'label'       => __('Link-URL Trust-Badge 1 (optional)', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_footer_trust_badge_2', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_footer_trust_badge_2', [
        'label'       => __('Trust-Badge 2', 'restatify-base'),
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
        'label'       => __('Link-URL Trust-Badge 2 (optional)', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_footer_trust_badge_3', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'restatify_footer_trust_badge_3', [
        'label'       => __('Trust-Badge 3', 'restatify-base'),
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
        'label'       => __('Link-URL Trust-Badge 3 (optional)', 'restatify-base'),
        'section'     => 'restatify_footer',
        'type'        => 'url',
    ]);
    // ── Hero Section ────────────────────────────────────────────────────────
    $wp_customize->add_section( 'restatify_hero', [
        'title'       => __( 'Hero-Bereich', 'restatify-base' ),
        'priority'    => 30,
        'description' => __( 'Texte und Button-Links für den Hero-Block auf der Startseite.', 'restatify-base' ),
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
        'default'           => __( 'Strategische Begleitung, die Komplexität in nachhaltiges Wachstum verwandelt', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_tagline', [
        'label'       => __( 'Tagline (kleiner Text über der Überschrift)', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_heading', [
        'default'           => __( 'Unternehmensberatung', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_heading', [
        'label'       => __( 'Hauptüberschrift (H2)', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_text', [
        'default'           => __( 'Eine Unternehmensberatung unterstuetzt Organisationen dabei, Visionen zu schärfen, komplexe Herausforderungen zu loesen und neue Chancen zu erschließen.', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_text', [
        'label'       => __( 'Fließtext unter der Überschrift', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'textarea',
    ] );

    $wp_customize->add_setting( 'restatify_hero_btn1_label', [
        'default'           => __( 'Jetzt starten', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_btn1_label', [
        'label'       => __( 'Beschriftung primärer Button', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_btn1_url', [
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_btn1_url', [
        'label'       => __( 'URL primärer Button', 'restatify-base' ),
        'description' => __( 'Interner Anker (z.B. #pricing01-k) oder vollständige URL.', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_btn2_label', [
        'default'           => __( 'Jetzt Kontakt aufnehmen', 'restatify-base' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_btn2_label', [
        'label'       => __( 'Beschriftung sekundärer Button', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'restatify_hero_btn2_url', [
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'restatify_hero_btn2_url', [
        'label'       => __( 'URL sekundärer Button', 'restatify-base' ),
        'description' => __( 'Interner Anker (z.B. #contacts01-c) oder vollständige URL.', 'restatify-base' ),
        'section'     => 'restatify_hero',
        'type'        => 'text',
    ] );
}
add_action('customize_register', 'restatify_customize_register');


