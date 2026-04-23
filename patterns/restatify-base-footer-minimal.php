<?php
/**
 * Title: Restatify Base Minimal Footer
 * Slug: restatify-base-footer-minimal
 * Categories: footer
 * Block Types: core/template-part/footer
 */

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

$imprint_url = $build_local_url($is_german ? 'impressum/' : 'imprint/');
$privacy_url = get_privacy_policy_url();
if (empty($privacy_url)) {
    $privacy_url = $build_local_url($is_german ? 'datenschutz/' : 'privacy-policy/');
}

$custom_imprint_url = trim((string) get_theme_mod('restatify_maintenance_imprint_url', ''));
if ($custom_imprint_url !== '') {
    if (function_exists('restatify_resolve_link_target')) {
        $imprint_url = (string) restatify_resolve_link_target($custom_imprint_url);
    } else {
        $imprint_url = $custom_imprint_url;
    }
}

$custom_privacy_url = trim((string) get_theme_mod('restatify_maintenance_privacy_url', ''));
if ($custom_privacy_url !== '') {
    if (function_exists('restatify_resolve_link_target')) {
        $privacy_url = (string) restatify_resolve_link_target($custom_privacy_url);
    } else {
        $privacy_url = $custom_privacy_url;
    }
}

$legacy_show_legal_links = ! empty(get_theme_mod('restatify_maintenance_show_legal_links', false));
$show_imprint_link = ! empty(get_theme_mod('restatify_maintenance_show_imprint_link', false)) || $legacy_show_legal_links;
$show_privacy_link = ! empty(get_theme_mod('restatify_maintenance_show_privacy_link', false)) || $legacy_show_legal_links;

$company_name = trim((string) get_theme_mod('restatify_maintenance_company_name', ''));
$represented_by = trim((string) get_theme_mod('restatify_maintenance_represented_by', ''));
$address_lines = preg_split('/\r\n|\r|\n/', (string) get_theme_mod('restatify_maintenance_address', ''));
$address_lines = array_values(array_filter(array_map('trim', (array) $address_lines), static function (string $line): bool {
    return $line !== '';
}));
$register_info = trim((string) get_theme_mod('restatify_maintenance_register_info', ''));
$vat_id = trim((string) get_theme_mod('restatify_maintenance_vat_id', ''));
$contact_email = sanitize_email((string) get_theme_mod('restatify_maintenance_contact_email', ''));
$contact_phone = trim((string) get_theme_mod('restatify_maintenance_contact_phone', ''));
$contact_phone_href = function_exists('restatify_get_tel_href') ? restatify_get_tel_href($contact_phone) : '';

$has_imprint_data = $company_name !== ''
    || $represented_by !== ''
    || ! empty($address_lines)
    || $register_info !== ''
    || $vat_id !== ''
    || $contact_email !== ''
    || $contact_phone !== '';

$disclaimer_text = trim((string) get_theme_mod('restatify_maintenance_disclaimer', ''));

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

$legal_links = [];
if ($show_privacy_link) {
    $legal_links[] = [
        'label' => $localize('Datenschutz', 'Privacy Policy'),
        'url' => $privacy_url,
    ];
}
if ($show_imprint_link) {
    $legal_links[] = [
        'label' => $localize('Impressum', 'Imprint'),
        'url' => $imprint_url,
    ];
}
?>
<section class="restatify-minimal-footer">
    <div class="restatify-minimal-footer__inner">
        <?php if (! empty($social_links)) : ?>
            <div class="restatify-minimal-footer__social" aria-label="<?php echo esc_attr($localize('Social Media', 'Social media')); ?>">
                <?php foreach ($social_links as $social_link) : ?>
                    <a class="restatify-minimal-footer__social-link" href="<?php echo esc_url($social_link['url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($social_link['label']); ?>">
                        <span class="mbr-iconfont socicon <?php echo esc_attr($social_link['icon']); ?>" aria-hidden="true"></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="restatify-minimal-footer__meta">
            <?php if ($has_imprint_data) : ?>
                <div class="restatify-minimal-footer__imprint" aria-label="<?php echo esc_attr($localize('Impressum', 'Imprint')); ?>">
                    <p class="restatify-minimal-footer__imprint-title"><?php echo esc_html($localize('Impressum', 'Imprint')); ?></p>
                    <?php if ($company_name !== '') : ?>
                        <p><?php echo esc_html($company_name); ?></p>
                    <?php endif; ?>
                    <?php if ($represented_by !== '') : ?>
                        <p><?php echo esc_html($localize('Vertreten durch: ', 'Represented by: ') . $represented_by); ?></p>
                    <?php endif; ?>
                    <?php foreach ($address_lines as $address_line) : ?>
                        <p><?php echo esc_html($address_line); ?></p>
                    <?php endforeach; ?>
                    <?php if ($register_info !== '') : ?>
                        <p><?php echo esc_html($register_info); ?></p>
                    <?php endif; ?>
                    <?php if ($vat_id !== '') : ?>
                        <p><?php echo esc_html($localize('USt-IdNr.: ', 'VAT ID: ') . $vat_id); ?></p>
                    <?php endif; ?>
                    <?php if ($contact_email !== '') : ?>
                        <p><a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a></p>
                    <?php endif; ?>
                    <?php if ($contact_phone !== '') : ?>
                        <p>
                            <?php if ($contact_phone_href !== '') : ?>
                                <a href="<?php echo esc_url($contact_phone_href); ?>"><?php echo esc_html($contact_phone); ?></a>
                            <?php else : ?>
                                <?php echo esc_html($contact_phone); ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($disclaimer_text !== '') : ?>
                <p class="restatify-minimal-footer__disclaimer"><?php echo esc_html($disclaimer_text); ?></p>
            <?php endif; ?>

            <?php if (! empty($legal_links)) : ?>
                <nav class="restatify-minimal-footer__legal" aria-label="<?php echo esc_attr($localize('Rechtliches', 'Legal')); ?>">
                    <?php foreach ($legal_links as $legal_link) : ?>
                        <a href="<?php echo esc_url($legal_link['url']); ?>"><?php echo esc_html($legal_link['label']); ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</section>