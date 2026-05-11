<?php
/**
 * Theme assets enqueue.
 */

function restatify_get_cookie_consent_state() {
    if (!empty($_COOKIE['restatify_cookie_consent'])) {
        $state = sanitize_key($_COOKIE['restatify_cookie_consent']);
        if (in_array($state, ['accepted', 'rejected'], true)) {
            return $state;
        }
    }

    // Backward compatibility with previous cookie alert implementation.
    if (!empty($_COOKIE['cookiesDirective'])) {
        return 'accepted';
    }

    return 'pending';
}

function restatify_get_cookie_privacy_url() {
    $privacy_url = trim((string) get_theme_mod('restatify_cookie_privacy_url', ''));

    if ($privacy_url === '') {
        $privacy_url = get_privacy_policy_url();
    }

    if (empty($privacy_url)) {
        $privacy_url = home_url('/privacy-policy/');
    }

    return (string) $privacy_url;
}

function restatify_normalize_path_for_compare($path) {
    $path = rawurldecode((string) $path);
    $path = trim($path);

    if ($path === '') {
        return '/';
    }

    $path = '/' . ltrim($path, '/');
    $normalized = untrailingslashit($path);

    return $normalized === '' ? '/' : $normalized;
}

function restatify_is_cookie_privacy_request() {
    if (is_admin()) {
        return false;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
    $request_path = wp_parse_url($request_uri, PHP_URL_PATH);
    $request_path = restatify_normalize_path_for_compare($request_path);

    $privacy_url = restatify_get_cookie_privacy_url();
    $privacy_path = wp_parse_url($privacy_url, PHP_URL_PATH);
    $privacy_path = restatify_normalize_path_for_compare($privacy_path);

    return $request_path === $privacy_path;
}

function restatify_get_cookie_consent_ui_state() {
    if (restatify_is_cookie_privacy_request()) {
        // Privacy page must remain reachable without prior consent.
        return 'rejected';
    }

    $state = restatify_get_cookie_consent_state();

    // In maintenance mode, the banner can be intentionally suppressed.
    // Avoid applying the pending-state page lock when no banner is rendered.
    if (
        $state === 'pending'
        && function_exists('restatify_is_lightstart_maintenance_request')
        && restatify_is_lightstart_maintenance_request()
        && function_exists('restatify_should_show_cookie_banner_in_maintenance')
        && ! restatify_should_show_cookie_banner_in_maintenance()
    ) {
        return 'accepted';
    }

    return $state;
}

function restatify_theme_assets() {
    $uri = get_template_directory_uri() . '/assets';
    $consent_state = restatify_get_cookie_consent_state();
    $consent_ui_state = restatify_get_cookie_consent_ui_state();

    if (restatify_is_cookie_privacy_request()) {
        $consent_state = 'rejected';
        $consent_ui_state = 'rejected';
    }

    // Styles (Mobirise order)
    wp_enqueue_style('mobirise-icons', $uri . '/web/assets/mobirise-icons2/mobirise2.css', [], filemtime(get_template_directory() . '/assets/web/assets/mobirise-icons2/mobirise2.css'));
    wp_enqueue_style('icon54-v2', $uri . '/icon54-v2/style.css', [], filemtime(get_template_directory() . '/assets/icon54-v2/style.css'));
    wp_enqueue_style('iconsmind', $uri . '/iconsMind/style.css', [], filemtime(get_template_directory() . '/assets/iconsMind/style.css'));
    wp_enqueue_style('bootstrap', $uri . '/bootstrap/css/bootstrap.min.css', [], null);
    wp_enqueue_style('dropdown', $uri . '/dropdown/css/style.css', ['bootstrap'], null);
    wp_enqueue_style('socicon', $uri . '/socicon/css/styles.css', [], filemtime(get_template_directory() . '/assets/socicon/css/styles.css'));
    wp_enqueue_style('mobirise-main', $uri . '/theme/css/style.css', [], null);
    wp_enqueue_style('mobirise-additional', $uri . '/mobirise/css/mbr-additional.css', ['mobirise-main'], null);

    // Theme overrides LAST
    wp_enqueue_style('restatify-style', get_stylesheet_uri(), ['mobirise-additional'], filemtime(get_template_directory() . '/style.css'));

    // Scripts
    wp_enqueue_script('bootstrap-js', $uri . '/bootstrap/js/bootstrap.bundle.min.js', [], null, true);
    wp_enqueue_script('dropdown-js', $uri . '/dropdown/js/navbar-dropdown.js', ['bootstrap-js'], null, true);
    wp_enqueue_script('restatify-theme-mode-switcher', $uri . '/theme/js/theme-mode-switcher.js', ['dropdown-js'], filemtime(get_template_directory() . '/assets/theme/js/theme-mode-switcher.js'), true);

    if ($consent_state === 'accepted') {
        wp_enqueue_script('embla-js', $uri . '/embla/embla.min.js', [], null, true);
        wp_enqueue_script('theme-script', $uri . '/theme/js/script.js', ['bootstrap-js'], null, true);
        wp_enqueue_script('restatify-cta-popup', $uri . '/theme/js/cta-menu-popup.js', ['theme-script'], filemtime(get_template_directory() . '/assets/theme/js/cta-menu-popup.js'), true);
    }

    wp_enqueue_script('restatify-cookie-consent', $uri . '/theme/js/cookie-consent.js', [], filemtime(get_template_directory() . '/assets/theme/js/cookie-consent.js'), true);

    $privacy_url = esc_url(restatify_get_cookie_privacy_url());

    $current_lang = function_exists('pll_current_language') ? pll_current_language('slug') : '';
    $locale = strtolower((string) get_locale());
    $is_german = $current_lang === 'de' || strpos($locale, 'de') === 0;

    if ($is_german) {
        $default_cookie_title = 'Cookie-Einstellungen';
        $default_cookie_message = sprintf('Wir verwenden optionale Cookies für dynamische Inhalte. Bei Ablehnung bleiben nur technisch notwendige Funktionen aktiv. Details in der <a href="%s">Datenschutzerklärung</a>.', $privacy_url);
        $default_accept_text = 'Akzeptieren';
        $default_reject_text = 'Ablehnen';
        $default_manage_text = 'Cookie-Einstellungen';
    } else {
        $default_cookie_title = 'Cookie Preferences';
        $default_cookie_message = sprintf('We use optional cookies for dynamic content. If you reject, only technically required functions remain active. Details in our <a href="%s">privacy policy</a>.', $privacy_url);
        $default_accept_text = 'Accept';
        $default_reject_text = 'Reject';
        $default_manage_text = 'Cookie settings';
    }

    $cookie_title = trim((string) get_theme_mod('restatify_cookie_title', ''));
    if ($cookie_title === '') {
        $cookie_title = $default_cookie_title;
    }

    $cookie_message = trim((string) get_theme_mod('restatify_cookie_message', ''));
    if ($cookie_message === '') {
        $cookie_message = $default_cookie_message;
    } else {
        $cookie_message = str_replace('%privacy_url%', $privacy_url, $cookie_message);
    }

    $accept_text = trim((string) get_theme_mod('restatify_cookie_accept_text', ''));
    if ($accept_text === '') {
        $accept_text = $default_accept_text;
    }

    $reject_text = trim((string) get_theme_mod('restatify_cookie_reject_text', ''));
    if ($reject_text === '') {
        $reject_text = $default_reject_text;
    }

    $manage_text = trim((string) get_theme_mod('restatify_cookie_manage_text', ''));
    if ($manage_text === '') {
        $manage_text = $default_manage_text;
    }

    wp_localize_script('restatify-cookie-consent', 'restatifyCookieConsent', [
        'state' => $consent_ui_state,
        'title' => $cookie_title,
        'message' => $cookie_message,
        'acceptText' => $accept_text,
        'rejectText' => $reject_text,
        'manageText' => $manage_text,
        'reloadOnDecision' => true,
    ]);

    $GLOBALS['restatify_cookie_banner_data'] = [
        'state' => $consent_ui_state,
        'title' => $cookie_title,
        'message' => $cookie_message,
        'accept_text' => $accept_text,
        'reject_text' => $reject_text,
    ];
}
add_action('wp_enqueue_scripts', 'restatify_theme_assets');

function restatify_cookie_consent_body_class($classes) {
    $state = restatify_get_cookie_consent_ui_state();
    $classes[] = 'restatify-consent-' . $state;
    return $classes;
}
add_filter('body_class', 'restatify_cookie_consent_body_class');

function restatify_render_cookie_banner() {
    if (empty($GLOBALS['restatify_cookie_banner_data']) || !is_array($GLOBALS['restatify_cookie_banner_data'])) {
        return;
    }

    if (
        function_exists('restatify_is_lightstart_maintenance_request')
        && restatify_is_lightstart_maintenance_request()
        && function_exists('restatify_should_show_cookie_banner_in_maintenance')
        && !restatify_should_show_cookie_banner_in_maintenance()
    ) {
        return;
    }

    if (restatify_is_cookie_privacy_request()) {
        return;
    }

    $banner = $GLOBALS['restatify_cookie_banner_data'];
    $is_pending = ($banner['state'] ?? 'pending') === 'pending';
    ?>
        <div id="restatify-cookie-backdrop" class="restatify-cookie-backdrop<?php echo $is_pending ? '' : ' is-hidden'; ?>" aria-hidden="<?php echo $is_pending ? 'false' : 'true'; ?>"></div>
    <div id="restatify-cookie-banner" class="restatify-cookie-banner<?php echo $is_pending ? '' : ' is-hidden'; ?>" role="dialog" aria-live="polite" aria-label="<?php echo esc_attr($banner['title']); ?>"<?php echo $is_pending ? '' : ' hidden'; ?>>
      <div class="restatify-cookie-banner__content">
        <p class="restatify-cookie-banner__title"><?php echo esc_html($banner['title']); ?></p>
        <p class="restatify-cookie-banner__text"><?php echo wp_kses_post($banner['message']); ?></p>
      </div>
      <div class="restatify-cookie-banner__actions">
        <button type="button" class="restatify-cookie-btn restatify-cookie-btn--ghost" data-cookie-action="reject"><?php echo esc_html($banner['reject_text']); ?></button>
        <button type="button" class="restatify-cookie-btn restatify-cookie-btn--primary" data-cookie-action="accept"><?php echo esc_html($banner['accept_text']); ?></button>
      </div>
    </div>
    <?php
}
add_action('wp_footer', 'restatify_render_cookie_banner', 100);
