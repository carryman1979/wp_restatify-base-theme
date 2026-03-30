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

$label_text = $localize('Bleiben wir in Verbindung', 'Let us stay connected');
$title_default = $localize('Strategie, die mit Ihrem Unternehmen mitwaechst.', 'Strategy that scales with your business.');
$title_text = trim((string) get_theme_mod('restatify_footer_title', $title_default));
if ($title_text === '') {
    $title_text = $title_default;
}
$title_text = restatify_translate_polylang_string($title_text);

$description_default = $localize(
    'Wir unterstuetzen Sie bei Positionierung, Wachstum und Umsetzung - strukturiert, messbar und hands-on.',
    'We help you with positioning, growth, and implementation in a structured, measurable, hands-on way.'
);
$description_text = trim((string) get_theme_mod('restatify_footer_description', $description_default));
if ($description_text === '') {
    $description_text = $description_default;
}
$description_text = restatify_translate_polylang_string($description_text);

$cookie_settings_text = $localize('Cookie-Einstellungen', 'Cookie settings');

$phone_raw = (string) get_theme_mod('restatify_footer_phone', '');
$phone_display = restatify_sanitize_footer_phone($phone_raw);
$phone_href = function_exists('restatify_get_tel_href') ? restatify_get_tel_href($phone_display) : '';

$email_raw = trim((string) get_theme_mod('restatify_footer_email', ''));
$email_sanitized = sanitize_email($email_raw);
$email_href = $email_sanitized !== '' ? 'mailto:' . $email_sanitized : '';

$fax_raw = (string) get_theme_mod('restatify_footer_fax', '');
$fax_display = restatify_sanitize_footer_phone($fax_raw);
$fax_href = function_exists('restatify_get_tel_href') ? restatify_get_tel_href($fax_display) : '';

$vcard_url = restatify_translate_polylang_string((string) get_theme_mod('restatify_footer_vcard_url', ''));
$vcard_url = esc_url($vcard_url);
$vcard_text_default = $localize('vCard Gruender', 'Founder vCard');
$vcard_text = trim((string) get_theme_mod('restatify_footer_vcard_text', $vcard_text_default));
if ($vcard_text === '') {
    $vcard_text = $vcard_text_default;
}
$vcard_text = restatify_translate_polylang_string($vcard_text);

$contact_section_items = [];
if ($phone_display !== '' && $phone_href !== '') {
    $contact_section_items[] = [
        'label' => $phone_display,
        'url' => $phone_href,
        'icon' => 'mobi-mbri-phone',
    ];
}

if ($email_sanitized !== '' && $email_href !== '') {
    $contact_section_items[] = [
        'label' => $email_sanitized,
        'url' => $email_href,
        'icon' => 'mobi-mbri-letter',
    ];
}

if ($fax_display !== '' && $fax_href !== '') {
    $contact_section_items[] = [
        'label' => $fax_display,
        'url' => $fax_href,
        'icon' => 'mobi-mbri-print',
    ];
}

if ($vcard_url !== '') {
    $contact_section_items[] = [
        'label' => $vcard_text,
        'url' => $vcard_url,
        'icon' => 'mobi-mbri-contact-form',
        'class' => 'restatify-footer-vcard-item',
    ];
}

$social_links = [];
$social_candidates = [
    [
        'url' => (string) get_theme_mod('restatify_footer_social_linkedin', ''),
        'icon' => 'socicon-linkedin',
        'label' => 'LinkedIn',
    ],
    [
        'url' => (string) get_theme_mod('restatify_footer_social_xing', ''),
        'icon' => 'socicon-xing',
        'label' => 'Xing',
    ],
    [
        'url' => (string) get_theme_mod('restatify_footer_social_facebook', ''),
        'icon' => 'socicon-facebook',
        'label' => 'Facebook',
    ],
];

foreach ($social_candidates as $candidate) {
    $url = esc_url($candidate['url']);
    if ($url !== '') {
        $social_links[] = [
            'url' => $url,
            'icon' => $candidate['icon'],
            'label' => $candidate['label'],
        ];
    }
}

$trust_badges = [];
for ($badge_index = 1; $badge_index <= 3; $badge_index++) {
    $badge_value = get_theme_mod('restatify_footer_trust_badge_' . $badge_index, 0);
    $badge_link_url = restatify_translate_polylang_string((string) get_theme_mod('restatify_footer_trust_badge_' . $badge_index . '_url', ''));
    $badge_link_url = esc_url($badge_link_url);
    $badge_id = absint($badge_value);

    if ($badge_id > 0) {
        $trust_badges[] = [
            'id' => $badge_id,
            'url' => '',
            'link_url' => $badge_link_url,
        ];
        continue;
    }

    $badge_url = esc_url((string) $badge_value);
    if ($badge_url !== '') {
        $trust_badges[] = [
            'id' => 0,
            'url' => $badge_url,
            'link_url' => $badge_link_url,
        ];
    }
}

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
    [
        'title' => $localize('Kontakt', 'Contact'),
        'items' => $contact_section_items,
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
                                <a<?php echo !empty($item['class']) ? ' class="' . esc_attr($item['class']) . '"' : ''; ?> href="<?php echo esc_url($item['url']); ?>">
                                    <?php if (!empty($item['icon'])) : ?>
                                        <span class="restatify-footer-item-icon mbr-iconfont <?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></span>
                                    <?php endif; ?>
                                    <span><?php echo esc_html($item['label']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="border-wrap"></div>

        <div class="row align-items-center">
            <div class="col-12 col-lg-6 card">
                <div class="restatify-footer-meta">
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
            </div>
            <div class="col-12 col-lg-6">
                <?php if (!empty($trust_badges)) : ?>
                    <div class="restatify-trust-badges" aria-label="<?php echo esc_attr($localize('Trust-Badges', 'Trust badges')); ?>">
                        <?php foreach ($trust_badges as $badge) : ?>
                            <span class="restatify-trust-badge">
                                <?php if (!empty($badge['link_url'])) : ?>
                                    <a href="<?php echo esc_url($badge['link_url']); ?>" target="_blank" rel="noopener noreferrer">
                                <?php endif; ?>

                                <?php if (!empty($badge['id'])) : ?>
                                    <?php echo wp_kses_post(wp_get_attachment_image((int) $badge['id'], 'medium', false, ['class' => 'restatify-trust-badge-image'])); ?>
                                <?php elseif (!empty($badge['url'])) : ?>
                                    <img class="restatify-trust-badge-image" src="<?php echo esc_url($badge['url']); ?>" alt="" loading="lazy" decoding="async" />
                                <?php endif; ?>

                                <?php if (!empty($badge['link_url'])) : ?>
                                    </a>
                                <?php endif; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($social_links)) : ?>
                    <div class="social-wrapper">
                        <div class="social-wrap">
                            <?php foreach ($social_links as $social_link) : ?>
                                <a class="iconfont-wrapper" href="<?php echo esc_url($social_link['url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($social_link['label']); ?>">
                                    <span class="p-2 mbr-iconfont socicon <?php echo esc_attr($social_link['icon']); ?>"></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>