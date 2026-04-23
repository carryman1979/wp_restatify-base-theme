<?php
/**
 * Title: Restatify Base Minimal Header
 * Slug: restatify-base-header-minimal
 * Categories: header
 * Block Types: core/template-part/header
 */

$fallback_logo_src = get_theme_file_uri('/assets/images/wort-bildmarke20v320full20-20white20shine-96x31.webp');
$site_name = get_bloginfo('name');
$header_text = trim((string) get_theme_mod('restatify_maintenance_header_text', ''));
if ($header_text === '') {
    $header_text = trim((string) get_bloginfo('description'));
}

$logo_id = (int) get_theme_mod('custom_logo');
$logo_src = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
$logo_srcset = $logo_id ? wp_get_attachment_image_srcset($logo_id, 'full') : '';

if ($logo_src === '') {
    $logo_src = $fallback_logo_src;
}
?>
<section class="restatify-minimal-header">
    <div class="restatify-minimal-header__inner">
        <a class="restatify-minimal-header__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr($site_name); ?>">
            <img
                src="<?php echo esc_url($logo_src); ?>"
                <?php echo $logo_srcset !== '' ? 'srcset="' . esc_attr($logo_srcset) . '"' : ''; ?>
                alt="<?php echo esc_attr($site_name); ?>"
                loading="eager"
                decoding="async"
                fetchpriority="high"
            />
            <?php if ($header_text !== '') : ?>
                <span class="restatify-minimal-header__title"><?php echo esc_html($header_text); ?></span>
            <?php endif; ?>
        </a>
    </div>
</section>