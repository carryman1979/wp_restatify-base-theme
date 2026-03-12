<?php
/**
 * Title: Restatify Base-Header
 * Slug: restatify-base-header
 * Categories: header
 * Block Types: core/template-part/header
 */
$fallback_logo_src = get_theme_file_uri('/assets/images/wort-bildmarke20v320full20-20white20shine-96x31.webp');
$uploads = wp_upload_dir();

if (!empty($uploads['basedir']) && !empty($uploads['baseurl'])) {
    $preferred_fallbacks = [
        '2026/02/Wort-Bildmarke-V3-full.png',
        '2026/02/Wort-Bildmarke-V3-full-scaled.png',
        '2026/02/Wort-Bildmarke-V3-full-dark.png',
    ];

    foreach ($preferred_fallbacks as $relative_path) {
        $candidate_path = trailingslashit($uploads['basedir']) . $relative_path;
        if (file_exists($candidate_path)) {
            $fallback_logo_src = trailingslashit($uploads['baseurl']) . $relative_path;
            break;
        }
    }
}
$site_name = get_bloginfo('name');
$site_tagline = get_bloginfo('description');
$cta_button_label = trim((string) get_theme_mod('restatify_cta_button_label', __('Start now', 'restatify-base')));
$cta_button_label = $cta_button_label !== '' ? $cta_button_label : __('Start now', 'restatify-base');
if (function_exists('pll__')) {
    $cta_button_label = pll__($cta_button_label);
}
$has_cta_menu = has_nav_menu('cta_button_menu');
$cta_menu_html = '';

if ($has_cta_menu) {
    $cta_menu_html = wp_nav_menu([
        'theme_location' => 'cta_button_menu',
        'container'      => false,
        'menu_class'     => 'restatify-cta-menu-list',
        'depth'          => 2,
        'echo'           => false,
        'fallback_cb'    => false,
    ]);
}

$social_menu_html = '';
if (has_nav_menu('header_social_menu')) {
    $social_menu_html = wp_nav_menu([
        'theme_location' => 'header_social_menu',
        'container'      => false,
        'echo'           => false,
        'fallback_cb'    => false,
        'depth'          => 1,
        'items_wrap'     => '%3$s',
        'walker'         => class_exists('Restatify_Walker_Social_Menu') ? new Restatify_Walker_Social_Menu() : '',
    ]);
}

$language_items = [];
if (function_exists('pll_the_languages')) {
    $raw_languages = pll_the_languages([
        'raw' => 1,
        'hide_if_no_translation' => 0,
    ]);

    if (is_array($raw_languages)) {
        foreach ($raw_languages as $language) {
            if (!empty($language['url']) && !empty($language['slug'])) {
                $language_items[] = [
                    'slug' => strtoupper((string) $language['slug']),
                    'url' => (string) $language['url'],
                    'current' => !empty($language['current_lang']),
                    'name' => !empty($language['name']) ? (string) $language['name'] : (string) $language['slug'],
                ];
            }
        }
    }
}

$current_language = null;
if (!empty($language_items)) {
    $current_language = $language_items[0];
    foreach ($language_items as $language_item) {
        if (!empty($language_item['current'])) {
            $current_language = $language_item;
            break;
        }
    }
}

$light_logo_id = (int) get_theme_mod('custom_logo');
$dark_logo_id  = (int) get_theme_mod('restatify_dark_logo');

$light_logo_src = '';
$light_logo_srcset = '';
$dark_logo_src = '';
$dark_logo_srcset = '';

if ($light_logo_id) {
    $light_logo_src = wp_get_attachment_image_url($light_logo_id, 'full');
    $light_logo_srcset = wp_get_attachment_image_srcset($light_logo_id, 'full');
}

if ($dark_logo_id) {
    $dark_logo_src = wp_get_attachment_image_url($dark_logo_id, 'full');
    $dark_logo_srcset = wp_get_attachment_image_srcset($dark_logo_id, 'full');
}

if (empty($light_logo_src)) {
    $light_logo_src = $fallback_logo_src;
}

$logo_html = '';
if (!empty($dark_logo_src)) {
    $logo_html = sprintf(
        '<img class="logo-light" src="%1$s"%2$s alt="%3$s" loading="eager" decoding="async" fetchpriority="high" /><img class="logo-dark" src="%4$s"%5$s alt="%3$s" loading="eager" decoding="async" fetchpriority="high" />',
        esc_url($light_logo_src),
        !empty($light_logo_srcset) ? ' srcset="' . esc_attr($light_logo_srcset) . '"' : '',
        esc_attr($site_name),
        esc_url($dark_logo_src),
        !empty($dark_logo_srcset) ? ' srcset="' . esc_attr($dark_logo_srcset) . '"' : ''
    );
} else {
    $logo_html = sprintf(
        '<img src="%1$s"%2$s alt="%3$s" loading="eager" decoding="async" fetchpriority="high" />',
        esc_url($light_logo_src),
        !empty($light_logo_srcset) ? ' srcset="' . esc_attr($light_logo_srcset) . '"' : '',
        esc_attr($site_name)
    );
}
?>
<section data-bs-version="5.1" class="menu menu01 integrationm5" once="menu" id="menu01-0">
    <nav class="navbar navbar-dropdown navbar-fixed-top navbar-expand-lg transparent">
        <div class="content-wrap container-fluid">
            <div class="navbar-brand">
                <span class="navbar-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php echo $logo_html; ?>
                    </a>
                </span>
                <?php if (!empty($site_tagline)) : ?>
                    <span class="navbar-caption-wrap">
                        <span class="navbar-caption"><?php echo esc_html($site_tagline); ?></span>
                    </span>
                <?php endif; ?>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse" data-target="#navbarSupportedContent" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'restatify-base'); ?>">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-dropdown nav-right" data-app-modern-menu="true">
                    <?php
                    wp_list_pages([
                        'title_li'    => '',
                        'depth'       => 2,
                        'sort_column' => 'menu_order,post_title',
                        'walker'      => class_exists('Restatify_Walker_Nav_Menu') ? new Restatify_Walker_Nav_Menu() : '',
                    ]);
                    ?>
                </ul>

                <?php if (!empty($social_menu_html)) : ?>
                    <div class="icons-menu">
                        <?php echo wp_kses_post($social_menu_html); ?>
                    </div>
                <?php endif; ?>

                <div class="navbar-buttons mbr-section-btn restatify-cta">
                    <?php if (!empty($cta_menu_html)) : ?>
                        <button
                            class="btn btn-white display-7 restatify-cta-toggle"
                            type="button"
                            aria-expanded="false"
                            aria-controls="restatify-cta-popup"
                        >
                            <?php echo esc_html($cta_button_label); ?>
                        </button>
                        <div id="restatify-cta-popup" class="restatify-cta-popup" hidden>
                            <?php echo wp_kses_post($cta_menu_html); ?>
                        </div>
                    <?php else : ?>
                        <a class="btn btn-white display-7" href="#"><?php echo esc_html($cta_button_label); ?></a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($language_items)) : ?>
                    <details class="restatify-lang-switch">
                        <summary class="restatify-lang-toggle" aria-label="<?php esc_attr_e('Language switcher', 'restatify-base'); ?>">
                            <span class="restatify-lang-current"><?php echo esc_html($current_language['slug']); ?></span>
                            <span class="restatify-lang-caret" aria-hidden="true"></span>
                        </summary>
                        <div class="restatify-lang-menu" role="listbox" aria-label="<?php esc_attr_e('Languages', 'restatify-base'); ?>">
                            <?php foreach ($language_items as $lang_item) : ?>
                                <a
                                    class="restatify-lang-link<?php echo $lang_item['current'] ? ' is-active' : ''; ?>"
                                    href="<?php echo esc_url($lang_item['url']); ?>"
                                    hreflang="<?php echo esc_attr(strtolower($lang_item['slug'])); ?>"
                                    lang="<?php echo esc_attr(strtolower($lang_item['slug'])); ?>"
                                    aria-current="<?php echo $lang_item['current'] ? 'true' : 'false'; ?>"
                                    aria-label="<?php echo esc_attr($lang_item['name']); ?>"
                                >
                                    <?php echo esc_html($lang_item['slug']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </details>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</section>