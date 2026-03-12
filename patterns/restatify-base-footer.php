<?php
/**
 * Title: Restatify Base Footer
 * Slug: restatify-base-footer
 * Categories: footer
 * Block Types: core/template-part/footer
 */

$site_name = get_bloginfo('name');
$site_description = get_bloginfo('description');
$current_year = gmdate('Y');

$current_lang = function_exists('pll_current_language') ? (string) pll_current_language('slug') : '';
$locale = strtolower((string) get_locale());
$is_german = $current_lang === 'de' || strpos($locale, 'de') === 0;

$home_url = function_exists('pll_home_url') ? (string) pll_home_url() : home_url('/');
$home_url = $home_url !== '' ? $home_url : home_url('/');

$localize = static function (string $de, string $en) use ($is_german): string {
    $value = $is_german ? $de : $en;

    if (function_exists('pll__')) {
        $translated = pll__($value);
        if (is_string($translated) && $translated !== '') {
            return $translated;
        }
    }

    return $value;
};

$build_local_url = static function (string $path) use ($home_url): string {
    return trailingslashit($home_url) . ltrim($path, '/');
};

$contact_url = $build_local_url($is_german ? 'kontakt/' : 'contact/');
$about_url = $build_local_url($is_german ? 'ueber-uns/' : 'about/');
$services_url = $build_local_url($is_german ? 'leistungen/' : 'services/');
$imprint_url = $build_local_url($is_german ? 'impressum/' : 'imprint/');

$privacy_url = get_privacy_policy_url();
if (empty($privacy_url)) {
    $privacy_url = $build_local_url($is_german ? 'datenschutz/' : 'privacy-policy/');
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

$label_text = $localize('Bleiben wir in Verbindung', 'Let us stay connected');
$title_text = $localize('Strategie, die mit Ihrem Unternehmen mitwaechst.', 'Strategy that scales with your business.');
$description_text = $localize(
    'Wir unterstuetzen Sie bei Positionierung, Wachstum und Umsetzung - strukturiert, messbar und hands-on.',
    'We help you with positioning, growth, and implementation in a structured, measurable, hands-on way.'
);
$cookie_settings_text = $localize('Cookie-Einstellungen', 'Cookie settings');

$sections = [
    [
        'title' => $localize('Unternehmen', 'Company'),
        'items' => [
            ['label' => $localize('Ueber uns', 'About'), 'url' => $about_url],
            ['label' => $localize('Kontakt', 'Contact'), 'url' => $contact_url],
        ],
    ],
    [
        'title' => $localize('Leistungen', 'Services'),
        'items' => [
            ['label' => $localize('Beratung', 'Consulting'), 'url' => $services_url],
            ['label' => $localize('Startseite', 'Home'), 'url' => $home_url],
        ],
    ],
    [
        'title' => $localize('Rechtliches', 'Legal'),
        'items' => [
            ['label' => $localize('Datenschutz', 'Privacy Policy'), 'url' => $privacy_url],
            ['label' => $localize('Impressum', 'Imprint'), 'url' => $imprint_url],
        ],
    ],
];

$social_links = [
    [
        'url' => 'https://www.linkedin.com/',
        'icon' => 'socicon-linkedin',
        'label' => 'LinkedIn',
    ],
    [
        'url' => 'https://www.instagram.com/',
        'icon' => 'socicon-instagram',
        'label' => 'Instagram',
    ],
    [
        'url' => 'https://www.youtube.com/',
        'icon' => 'socicon-youtube',
        'label' => 'YouTube',
    ],
];
?>
<section data-bs-version="5.1" class="footer2 integrationm5 cid-v910uKC3F3" once="footers" id="footer02-0">
    <div class="container-fluid">
        <div class="title-wrapper">
            <p class="mbr-label mbr-fonts-style display-7"><?php echo esc_html($label_text); ?></p>
            <h2 class="mbr-section-title mbr-fonts-style display-2"><strong><?php echo esc_html($title_text); ?></strong></h2>
            <p class="mbr-text mbr-fonts-style display-7"><?php echo esc_html($description_text); ?></p>
        </div>

        <div class="nav-wrapper">
            <?php foreach ($sections as $section) : ?>
                <div class="nav-wrap">
                    <p class="mbr-list-title mbr-fonts-style display-7"><strong><?php echo esc_html($section['title']); ?></strong></p>
                    <ul class="list mbr-fonts-style display-4">
                        <?php foreach ($section['items'] as $item) : ?>
                            <li class="item-wrap">
                                <a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="border-wrap"></div>

        <div class="row align-items-center">
            <div class="col-12 col-lg-6 card">
                <p class="mbr-copy mbr-fonts-style display-4">
                    © <?php echo esc_html($current_year); ?> <?php echo esc_html($site_name); ?><?php echo !empty($site_description) ? ' - ' . esc_html($site_description) : ''; ?>
                </p>
                <button
                    type="button"
                    id="restatify-cookie-reopen"
                    class="restatify-cookie-reopen restatify-cookie-reopen--inline"
                    data-cookie-open="true"
                    aria-controls="restatify-cookie-banner"
                >
                    <?php echo esc_html($cookie_settings_text); ?>
                </button>
            </div>
            <div class="col-12 col-lg-6">
                <div class="social-wrapper">
                    <div class="social-wrap">
                        <?php if (!empty($social_menu_html)) : ?>
                            <?php echo wp_kses_post($social_menu_html); ?>
                        <?php else : ?>
                            <?php foreach ($social_links as $social_link) : ?>
                                <a class="iconfont-wrapper" href="<?php echo esc_url($social_link['url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($social_link['label']); ?>">
                                    <span class="p-2 mbr-iconfont socicon <?php echo esc_attr($social_link['icon']); ?>"></span>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>