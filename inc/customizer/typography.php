<?php
/**
 * Dynamic typography CSS output.
 */

function restatify_output_typography_css() {
    $body_font = get_theme_mod('restatify_font_body', 'var(--wp--preset--font-family--ubuntu)');
    $headings_font = get_theme_mod('restatify_font_headings', 'var(--wp--preset--font-family--science-gothic)');
    $paragraph_font = get_theme_mod('restatify_font_paragraph', 'var(--wp--preset--font-family--ubuntu)');
    $links_font = get_theme_mod('restatify_font_links', 'var(--wp--preset--font-family--ubuntu)');

    $body_size = get_theme_mod('restatify_size_body', '1rem');
    $h1_size = get_theme_mod('restatify_size_h1', '3.2rem');
    $h2_size = get_theme_mod('restatify_size_h2', '2.4rem');
    $h3_size = get_theme_mod('restatify_size_h3', '1.8rem');
    $links_size = get_theme_mod('restatify_size_links', '1rem');
    $base_line_height = get_theme_mod('restatify_line_height_base', '1.5');

    $body_size_mobile = get_theme_mod('restatify_size_body_mobile', '');
    $h1_size_mobile = get_theme_mod('restatify_size_h1_mobile', '');
    $h2_size_mobile = get_theme_mod('restatify_size_h2_mobile', '');
    $h3_size_mobile = get_theme_mod('restatify_size_h3_mobile', '');
    $links_size_mobile = get_theme_mod('restatify_size_links_mobile', '');
    $base_line_height_mobile = get_theme_mod('restatify_line_height_base_mobile', '');

    $body_font = restatify_sanitize_font_choice($body_font);
    $headings_font = restatify_sanitize_font_choice($headings_font);
    $paragraph_font = restatify_sanitize_font_choice($paragraph_font);
    $links_font = restatify_sanitize_font_choice($links_font);

    $body_size = restatify_sanitize_font_size($body_size);
    $h1_size = restatify_sanitize_font_size($h1_size);
    $h2_size = restatify_sanitize_font_size($h2_size);
    $h3_size = restatify_sanitize_font_size($h3_size);
    $links_size = restatify_sanitize_font_size($links_size);
    $base_line_height = restatify_sanitize_line_height($base_line_height);

    $body_size_mobile = restatify_sanitize_optional_font_size($body_size_mobile);
    $h1_size_mobile = restatify_sanitize_optional_font_size($h1_size_mobile);
    $h2_size_mobile = restatify_sanitize_optional_font_size($h2_size_mobile);
    $h3_size_mobile = restatify_sanitize_optional_font_size($h3_size_mobile);
    $links_size_mobile = restatify_sanitize_optional_font_size($links_size_mobile);
    $base_line_height_mobile = restatify_sanitize_optional_line_height($base_line_height_mobile);

    $body_size_mobile_effective = $body_size_mobile !== '' ? $body_size_mobile : $body_size;
    $h1_size_mobile_effective = $h1_size_mobile !== '' ? $h1_size_mobile : $h1_size;
    $h2_size_mobile_effective = $h2_size_mobile !== '' ? $h2_size_mobile : $h2_size;
    $h3_size_mobile_effective = $h3_size_mobile !== '' ? $h3_size_mobile : $h3_size;
    $links_size_mobile_effective = $links_size_mobile !== '' ? $links_size_mobile : $links_size;
    $base_line_height_mobile_effective = $base_line_height_mobile !== '' ? $base_line_height_mobile : $base_line_height;
    ?>
    <style id="restatify-typography-overrides">
      body,
      input,
      textarea,
      select {
        font-family: <?php echo esc_html($body_font); ?> !important;
        font-size: <?php echo esc_html($body_size); ?>;
        line-height: <?php echo esc_html($base_line_height); ?>;
      }

      h1, h2, h3, h4, h5, h6,
      .mbr-section-title,
      .navbar-caption {
        font-family: <?php echo esc_html($headings_font); ?> !important;
      }

      h1 { font-size: <?php echo esc_html($h1_size); ?>; }
      h2 { font-size: <?php echo esc_html($h2_size); ?>; }
      h3 { font-size: <?php echo esc_html($h3_size); ?>; }

      p,
      li,
      .mbr-desc {
        font-family: <?php echo esc_html($paragraph_font); ?> !important;
      }

      a,
      .nav-link,
      .btn,
      .navbar-caption {
        font-family: <?php echo esc_html($links_font); ?> !important;
        font-size: <?php echo esc_html($links_size); ?>;
      }

      @media (max-width: 991.98px) {
        body,
        input,
        textarea,
        select {
          font-size: <?php echo esc_html($body_size_mobile_effective); ?>;
          line-height: <?php echo esc_html($base_line_height_mobile_effective); ?>;
        }

        h1 { font-size: <?php echo esc_html($h1_size_mobile_effective); ?>; }
        h2 { font-size: <?php echo esc_html($h2_size_mobile_effective); ?>; }
        h3 { font-size: <?php echo esc_html($h3_size_mobile_effective); ?>; }

        a,
        .nav-link,
        .btn,
        .navbar-caption {
          font-size: <?php echo esc_html($links_size_mobile_effective); ?>;
        }
      }
    </style>
    <?php
}
add_action('wp_head', 'restatify_output_typography_css', 1100);
