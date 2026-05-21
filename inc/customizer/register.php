<?php
/**
 * Customizer settings and controls.
 */

if (class_exists('WP_Customize_Control') && !class_exists('Restatify_Customize_Group_Divider_Control')) {
    class Restatify_Customize_Group_Divider_Control extends WP_Customize_Control {
        public $type = 'restatify_group_divider';

        public function render_content() {
            if (!empty($this->label)) {
                echo '<span class="customize-control-title" style="margin-top:10px;display:block;">' . esc_html($this->label) . '</span>';
            }

            if (!empty($this->description)) {
                echo '<span class="description customize-control-description" style="display:block;margin-bottom:8px;">' . esc_html($this->description) . '</span>';
            }

            echo '<hr style="margin:10px 0 12px;border:0;border-top:1px solid #dcdcde;" />';
        }
    }
}

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

    $wp_customize->add_section('restatify_footer', [
        'title'       => __('Footer Konfiguration', 'restatify-base'),
        'priority'    => 32,
        'description' => __('Slogan, Kontaktspalte, drei Linkspalten sowie optionale Social-Links, Trust-Badges und vCard in einem Bereich.', 'restatify-base'),
    ]);

    $wp_customize->add_section('restatify_cookie_consent', [
        'title'       => __('Cookie-Consent', 'restatify-base'),
        'priority'    => 34,
        'description' => __('Texte und Linkziel für den Cookie-Banner. In der Nachricht ist HTML erlaubt (z.B. Link-Tag). Du kannst optional %privacy_url% als Platzhalter verwenden.', 'restatify-base'),
    ]);

    $wp_customize->add_setting('restatify_cookie_privacy_url', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_url',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_cookie_privacy_url', [
        'label'       => __('Datenschutz-Link', 'restatify-base'),
        'description' => __('Leer lassen = WordPress-Datenschutzseite/Fallback.', 'restatify-base'),
        'section'     => 'restatify_cookie_consent',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('restatify_cookie_title', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_cookie_title', [
        'label'       => __('Banner-Titel', 'restatify-base'),
        'description' => __('Leer lassen = Theme-Standard je Sprache.', 'restatify-base'),
        'section'     => 'restatify_cookie_consent',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_cookie_message', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_cookie_message',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_cookie_message', [
        'label'       => __('Banner-Nachricht (HTML erlaubt)', 'restatify-base'),
        'description' => __('Leer lassen = Theme-Standard je Sprache. Hinweis: %privacy_url% wird automatisch ersetzt.', 'restatify-base'),
        'section'     => 'restatify_cookie_consent',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_setting('restatify_cookie_accept_text', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_cookie_accept_text', [
        'label'       => __('Buttontext Akzeptieren', 'restatify-base'),
        'description' => __('Leer lassen = Theme-Standard je Sprache.', 'restatify-base'),
        'section'     => 'restatify_cookie_consent',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_cookie_reject_text', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_cookie_reject_text', [
        'label'       => __('Buttontext Ablehnen', 'restatify-base'),
        'description' => __('Leer lassen = Theme-Standard je Sprache.', 'restatify-base'),
        'section'     => 'restatify_cookie_consent',
        'type'        => 'text',
    ]);

    $wp_customize->add_setting('restatify_cookie_manage_text', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_cookie_manage_text', [
        'label'       => __('Text Cookie-Einstellungen-Link', 'restatify-base'),
        'description' => __('Leer lassen = Theme-Standard je Sprache.', 'restatify-base'),
        'section'     => 'restatify_cookie_consent',
        'type'        => 'text',
    ]);

    if (function_exists('restatify_is_lightstart_available') && restatify_is_lightstart_available()) {
        $wp_customize->add_section('restatify_maintenance_mode', [
            'title'       => __('Wartungsmodus', 'restatify-base'),
            'priority'    => 35,
            'description' => __('Texte und rechtliche Angaben für den minimalen Header/Footer auf der LightStart-Wartungsseite. Link-Felder akzeptieren absolute URLs (https://...) und relative Pfade (z.B. /impressum, impressum.html, wp-content/uploads/datei.pdf).', 'restatify-base'),
        ]);

        $wp_customize->add_setting('restatify_maintenance_header_text', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_header_text', [
            'label'       => __('Header-Schriftzug neben Logo', 'restatify-base'),
            'description' => __('Wenn leer, wird die Unterzeile/Tagline der Website als Fallback verwendet.', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'text',
        ]);

        $wp_customize->add_setting('restatify_maintenance_company_name', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_company_name', [
            'label'       => __('Firmenname / Rechtsform', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'text',
        ]);

        $wp_customize->add_setting('restatify_maintenance_represented_by', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_represented_by', [
            'label'       => __('Vertreten durch', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'text',
        ]);

        $wp_customize->add_setting('restatify_maintenance_address', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_address', [
            'label'       => __('Anschrift (mehrzeilig)', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'textarea',
        ]);

        $wp_customize->add_setting('restatify_maintenance_register_info', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_register_info', [
            'label'       => __('Registerangaben', 'restatify-base'),
            'description' => __('z.B. Amtsgericht + Handelsregister-Nummer', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'text',
        ]);

        $wp_customize->add_setting('restatify_maintenance_vat_id', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_vat_id', [
            'label'       => __('USt-IdNr. (optional)', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'text',
        ]);

        $wp_customize->add_setting('restatify_maintenance_contact_email', [
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_contact_email', [
            'label'       => __('Kontakt-E-Mail', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'email',
        ]);

        $wp_customize->add_setting('restatify_maintenance_contact_phone', [
            'default'           => '',
            'sanitize_callback' => 'restatify_sanitize_footer_phone',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_contact_phone', [
            'label'       => __('Kontakt-Telefon (optional)', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'text',
        ]);

        $wp_customize->add_setting('restatify_maintenance_disclaimer', [
            'default'           => __('Dies ist eine vorübergehende Wartungsseite. Inhalte und Funktionen stehen nach Abschluss der Wartung wieder vollständig zur Verfügung.', 'restatify-base'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_disclaimer', [
            'label'       => __('Disclaimer-Text', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'textarea',
        ]);

        $wp_customize->add_setting('restatify_maintenance_show_imprint_link', [
            'default'           => false,
            'sanitize_callback' => 'restatify_sanitize_checkbox',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_show_imprint_link', [
            'label'       => __('Link für Impressum anzeigen', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'checkbox',
        ]);

        $wp_customize->add_setting('restatify_maintenance_imprint_url', [
            'default'           => 'wp-content/themes/wp_restatify-base-theme/fallback_imprint.html',
            'sanitize_callback' => 'restatify_sanitize_link_target',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_imprint_url', [
            'label'       => __('Impressum-Link (URL oder relativer Pfad)', 'restatify-base'),
            'description' => __('Beispiele: https://example.com/impressum, /impressum, impressum.html, wp-content/uploads/impressum.pdf. Leer = Theme-Standard.', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'text',
        ]);

        $wp_customize->add_setting('restatify_maintenance_show_privacy_link', [
            'default'           => false,
            'sanitize_callback' => 'restatify_sanitize_checkbox',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_show_privacy_link', [
            'label'       => __('Link für Datenschutz anzeigen', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'checkbox',
        ]);

        $wp_customize->add_setting('restatify_maintenance_privacy_url', [
            'default'           => 'wp-content/themes/wp_restatify-base-theme/fallback_privacy.html',
            'sanitize_callback' => 'restatify_sanitize_link_target',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control('restatify_maintenance_privacy_url', [
            'label'       => __('Datenschutz-Link (URL oder relativer Pfad)', 'restatify-base'),
            'description' => __('Beispiele: https://example.com/datenschutz, /datenschutz, privacy.html, wp-content/uploads/privacy.pdf. Leer = WordPress-Datenschutzseite/Theme-Fallback.', 'restatify-base'),
            'section'     => 'restatify_maintenance_mode',
            'type'        => 'text',
        ]);
    }

    $wp_customize->add_setting('restatify_footer_title', [
        'default'           => __('Strategie, die mit Ihrem Unternehmen mitwächst.', 'restatify-base'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_title', [
        'label'       => __('Footer-Slogan', 'restatify-base'),
        'description' => __('Hauptüberschrift im Footer-Titelbereich.', 'restatify-base'),
        'section'     => 'restatify_footer',
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
        'section'     => 'restatify_footer',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_setting('restatify_footer_contact_divider', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new Restatify_Customize_Group_Divider_Control($wp_customize, 'restatify_footer_contact_divider', [
        'label'       => __('Kontaktspalte', 'restatify-base'),
        'description' => __('Telefon, E-Mail und Fax fuer den Kontaktbereich im Footer.', 'restatify-base'),
        'section'     => 'restatify_footer',
        'settings'    => 'restatify_footer_contact_divider',
    ]));

    $wp_customize->add_setting('restatify_footer_phone', [
        'default'           => '',
        'sanitize_callback' => 'restatify_sanitize_footer_phone',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('restatify_footer_phone', [
        'label'       => __('Telefon für Schnellkontakt', 'restatify-base'),
        'description' => __('Beispiel: +49 123 456789', 'restatify-base'),
        'section'     => 'restatify_footer',
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
        'section'     => 'restatify_footer',
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
        'section'     => 'restatify_footer',
        'type'        => 'text',
    ]);

    $footer_column_defaults = function_exists('restatify_get_footer_column_defaults')
        ? restatify_get_footer_column_defaults()
        : [];

    for ($column_index = 1; $column_index <= 3; $column_index++) {
        $divider_setting = 'restatify_footer_col_' . $column_index . '_divider';
        $heading_setting = 'restatify_footer_col_' . $column_index . '_title';
        $heading_default = (string) ($footer_column_defaults[$column_index]['title'] ?? '');

        $wp_customize->add_setting($divider_setting, [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control(new Restatify_Customize_Group_Divider_Control($wp_customize, $divider_setting, [
            'label'       => sprintf(__('Linkspalte %d', 'restatify-base'), $column_index),
            'description' => __('Überschrift und bis zu vier Links konfigurieren.', 'restatify-base'),
            'section'     => 'restatify_footer',
            'settings'    => $divider_setting,
        ]));

        $wp_customize->add_setting($heading_setting, [
            'default'           => $heading_default,
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control($heading_setting, [
            'label'       => sprintf(__('Spalte %d - Überschrift', 'restatify-base'), $column_index),
            'description' => __('Leer lassen, um Theme-Standard zu verwenden.', 'restatify-base'),
            'section'     => 'restatify_footer',
            'type'        => 'text',
        ]);

        for ($link_index = 1; $link_index <= 4; $link_index++) {
            $title_setting = 'restatify_footer_col_' . $column_index . '_link_' . $link_index . '_title';
            $url_setting = 'restatify_footer_col_' . $column_index . '_link_' . $link_index . '_url';
            $link_defaults = (array) ($footer_column_defaults[$column_index]['links'][$link_index - 1] ?? []);
            $title_default = (string) ($link_defaults['title'] ?? '');
            $url_default = (string) ($link_defaults['url'] ?? '');

            $wp_customize->add_setting($title_setting, [
                'default'           => $title_default,
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ]);
            $wp_customize->add_control($title_setting, [
                'label'       => sprintf(__('Spalte %1$d - Link %2$d Titel', 'restatify-base'), $column_index, $link_index),
                'description' => __('Wenn leer, wird dieser Link im Footer ausgeblendet.', 'restatify-base'),
                'section'     => 'restatify_footer',
                'type'        => 'text',
            ]);

            $wp_customize->add_setting($url_setting, [
                'default'           => $url_default,
                'sanitize_callback' => 'restatify_sanitize_footer_url',
                'transport'         => 'refresh',
            ]);
            $wp_customize->add_control($url_setting, [
                'label'   => sprintf(__('Spalte %1$d - Link %2$d URL', 'restatify-base'), $column_index, $link_index),
                'section' => 'restatify_footer',
                'type'    => 'url',
            ]);
        }
    }

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


