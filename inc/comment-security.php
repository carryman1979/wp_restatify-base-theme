<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Configures and validates public-post comment spam protection.
 */
final class Restatify_Comment_Security {
    private const OPTION_NAME = 'restatify_comment_security';
    private const SETTINGS_GROUP = 'restatify_comment_security_group';
    private const PAGE_SLUG = 'restatify-comment-security';
    private const RECAPTCHA_ACTION = 'restatify_comment';

    /**
     * Register the admin settings and comment-form integration.
     */
    public static function init(): void {
        add_action('admin_menu', [self::class, 'add_settings_page']);
        add_action('admin_init', [self::class, 'register_settings']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        add_action('comment_form_after_fields', [self::class, 'render_fields']);
        add_action('comment_form_logged_in_after', [self::class, 'render_fields']);
        add_filter('preprocess_comment', [self::class, 'validate_comment'], 1);
    }

    /**
     * Add the comment-protection page under Settings.
     */
    public static function add_settings_page(): void {
        add_options_page(
            __('Kommentarsicherheit', 'restatify-base'),
            __('Kommentarsicherheit', 'restatify-base'),
            'manage_options',
            self::PAGE_SLUG,
            [self::class, 'render_settings_page']
        );
    }

    /**
     * Register the settings form and its fields.
     */
    public static function register_settings(): void {
        register_setting(self::SETTINGS_GROUP, self::OPTION_NAME, [
            'type' => 'array',
            'sanitize_callback' => [self::class, 'sanitize_settings'],
            'default' => self::defaults(),
        ]);

        add_settings_section(
            'restatify_comment_security_main',
            __('Kommentarschutz', 'restatify-base'),
            [self::class, 'render_settings_intro'],
            self::PAGE_SLUG
        );

        add_settings_field('honeypot', __('Honeypot', 'restatify-base'), [self::class, 'render_honeypot_setting'], self::PAGE_SLUG, 'restatify_comment_security_main');
        add_settings_field('provider', __('CAPTCHA-Anbieter', 'restatify-base'), [self::class, 'render_provider_setting'], self::PAGE_SLUG, 'restatify_comment_security_main');
        add_settings_field('recaptcha_keys', __('Google reCAPTCHA v3-Schlüssel', 'restatify-base'), [self::class, 'render_recaptcha_keys_setting'], self::PAGE_SLUG, 'restatify_comment_security_main');
        add_settings_field('turnstile_keys', __('Cloudflare-Turnstile-Schlüssel', 'restatify-base'), [self::class, 'render_turnstile_keys_setting'], self::PAGE_SLUG, 'restatify_comment_security_main');
    }

    /**
     * Sanitize settings while preserving saved secret keys when their fields are blank.
     *
     * @param mixed $input Submitted option value.
     * @return array<string,mixed>
     */
    public static function sanitize_settings($input): array {
        $input = is_array($input) ? $input : [];
        $saved = self::settings();
        $provider_input = $input['captcha_provider'] ?? 'none';
        $provider = sanitize_key(is_string($provider_input) ? $provider_input : 'none');

        $settings = [
            'honeypot' => !empty($input['honeypot']),
            'captcha_provider' => in_array($provider, ['none', 'recaptcha', 'turnstile'], true) ? $provider : 'none',
            'recaptcha_site_key' => self::sanitize_submitted_text($input, 'recaptcha_site_key', $saved['recaptcha_site_key']),
            'recaptcha_secret_key' => !empty($input['clear_recaptcha_secret'])
                ? ''
                : self::submitted_secret($input, 'recaptcha_secret_key', $saved),
            'turnstile_site_key' => self::sanitize_submitted_text($input, 'turnstile_site_key', $saved['turnstile_site_key']),
            'turnstile_secret_key' => !empty($input['clear_turnstile_secret'])
                ? ''
                : self::submitted_secret($input, 'turnstile_secret_key', $saved),
        ];

        return $settings;
    }

    /**
     * Enqueue CAPTCHA provider assets only on an open single-post comment form.
     */
    public static function enqueue_assets(): void {
        if (!is_singular('post') || !comments_open() || post_password_required()) {
            return;
        }

        $settings = self::settings();
        $provider = $settings['captcha_provider'];
        if (!in_array($provider, ['recaptcha', 'turnstile'], true)) {
            return;
        }

        $site_key = $provider === 'recaptcha'
            ? $settings['recaptcha_site_key']
            : $settings['turnstile_site_key'];
        if ($site_key === '') {
            return;
        }

        if ($provider === 'recaptcha') {
            if (!wp_script_is('google-recaptcha-v3', 'enqueued')) {
                wp_enqueue_script(
                    'google-recaptcha-v3',
                    'https://www.google.com/recaptcha/api.js?render=' . rawurlencode($site_key),
                    [],
                    null,
                    true
                );
            }
        } else {
            if (!wp_script_is('cloudflare-turnstile', 'enqueued')) {
                wp_enqueue_script(
                    'cloudflare-turnstile',
                    'https://challenges.cloudflare.com/turnstile/v0/api.js',
                    [],
                    null,
                    true
                );
            }
        }

        $script_path = get_template_directory() . '/assets/theme/js/comment-security.js';
        wp_enqueue_script(
            'restatify-comment-security',
            get_template_directory_uri() . '/assets/theme/js/comment-security.js',
            [],
            file_exists($script_path) ? (string) filemtime($script_path) : null,
            true
        );
        wp_localize_script('restatify-comment-security', 'RestatifyCommentSecurity', [
            'provider' => $provider,
            'siteKey' => $site_key,
            'action' => self::RECAPTCHA_ACTION,
            'message' => self::translate(__('Die Sicherheitsprüfung konnte nicht abgeschlossen werden. Bitte versuche es erneut.', 'restatify-base')),
        ]);
    }

    /**
     * Add anti-spam controls to the comments form.
     */
    public static function render_fields(): void {
        $settings = self::settings();
        $provider = $settings['captcha_provider'];
        ?>
        <?php if ($settings['honeypot']) : ?>
            <div class="restatify-comment-honeypot" aria-hidden="true">
                <label for="restatify-comment-website"><?php esc_html_e('Dieses Feld leer lassen', 'restatify-base'); ?></label>
                <input type="text" id="restatify-comment-website" name="restatify_comment_website" value="" tabindex="-1" autocomplete="off">
            </div>
        <?php endif; ?>
        <?php if ($provider === 'recaptcha' || $provider === 'turnstile') : ?>
            <div class="restatify-comment-captcha">
                <?php if ($provider === 'recaptcha') : ?>
                    <input type="hidden" name="restatify_comment_captcha_token" value="">
                <?php elseif ($settings['turnstile_site_key'] !== '') : ?>
                    <div class="cf-turnstile" data-sitekey="<?php echo esc_attr($settings['turnstile_site_key']); ?>" data-action="<?php echo esc_attr(self::RECAPTCHA_ACTION); ?>" data-response-field-name="restatify_comment_captcha_token"></div>
                <?php endif; ?>
                <p class="restatify-comment-captcha__error" role="alert" aria-live="assertive" hidden></p>
            </div>
        <?php endif; ?>
        <?php
        self::render_privacy_note();
    }

    /**
     * Disclose use of an external CAPTCHA provider in the visible comment form.
     */
    public static function render_privacy_note(): void {
        $provider = self::settings()['captcha_provider'];
        if ($provider === 'none') {
            return;
        }

        if ($provider === 'recaptcha') {
            $template = __('Der Spam-Schutz wird von Google reCAPTCHA bereitgestellt. Es gelten Googles Datenschutzerklärung (%1$s) und Nutzungsbedingungen (%2$s). Google kann IP-Adresse und Prüfdaten zur Spamabwehr verarbeiten.', 'restatify-base');
            $privacy_url = 'https://policies.google.com/privacy';
            $terms_url = 'https://policies.google.com/terms';
        } else {
            $template = __('Der Spam-Schutz wird von Cloudflare Turnstile bereitgestellt. Es gelten Cloudflares Datenschutzrichtlinie (%1$s) und Nutzungsbedingungen (%2$s). Cloudflare kann IP-Adresse und Prüfdaten zur Spamabwehr verarbeiten.', 'restatify-base');
            $privacy_url = 'https://www.cloudflare.com/privacypolicy/';
            $terms_url = 'https://www.cloudflare.com/website-terms/';
        }

        $privacy_link = sprintf(
            '<a href="%1$s" rel="noopener">%2$s</a>',
            esc_url($privacy_url),
            esc_html(self::translate(__('Datenschutzerklärung', 'restatify-base')))
        );
        $terms_link = sprintf(
            '<a href="%1$s" rel="noopener">%2$s</a>',
            esc_url($terms_url),
            esc_html(self::translate(__('Nutzungsbedingungen', 'restatify-base')))
        );
        $message = sprintf(self::translate($template), $privacy_link, $terms_link);
        echo '<p class="restatify-comment-security-notice">' . wp_kses($message, ['a' => ['href' => [], 'rel' => []]]) . '</p>';
    }

    /**
     * Validate the honeypot and CAPTCHA before WordPress stores a comment.
     *
     * @param array<string,mixed> $comment_data Comment data prepared by WordPress.
     * @return array<string,mixed>
     */
    public static function validate_comment(array $comment_data): array {
        $post_id = (int) ($comment_data['comment_post_ID'] ?? 0);
        $comment_type = (string) ($comment_data['comment_type'] ?? '');
        if (
            $post_id < 1
            || get_post_type($post_id) !== 'post'
            || ($comment_type !== '' && $comment_type !== 'comment')
        ) {
            return $comment_data;
        }

        $settings = self::settings();
        $honeypot_value = self::posted_text('restatify_comment_website');

        if ($settings['honeypot'] && $honeypot_value !== '') {
            self::reject_comment();
        }

        $provider = $settings['captcha_provider'];
        if ($provider === 'none') {
            return $comment_data;
        }

        $site_key = $provider === 'recaptcha'
            ? $settings['recaptcha_site_key']
            : $settings['turnstile_site_key'];
        $secret_key = $provider === 'recaptcha'
            ? $settings['recaptcha_secret_key']
            : $settings['turnstile_secret_key'];
        $token = self::posted_text('restatify_comment_captcha_token');

        if ($site_key === '' || $secret_key === '' || !class_exists('\\Restatify\\Shared\\Security\\CaptchaVerifier')) {
            self::reject_comment();
        }

        $remote_ip = isset($_SERVER['REMOTE_ADDR']) && is_string($_SERVER['REMOTE_ADDR'])
            ? sanitize_text_field($_SERVER['REMOTE_ADDR'])
            : '';
        $valid = \Restatify\Shared\Security\CaptchaVerifier::verify(
            $provider,
            $secret_key,
            $token,
            $remote_ip,
            0.5,
            self::RECAPTCHA_ACTION
        );

        if (!$valid) {
            self::reject_comment();
        }

        return $comment_data;
    }

    /**
     * Output the settings page.
     */
    public static function render_settings_page(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You are not allowed to manage comment security settings.', 'restatify-base'));
        }

        $settings = self::settings();
        $provider = $settings['captcha_provider'];
        $keys_missing = ($provider === 'recaptcha' && ($settings['recaptcha_site_key'] === '' || $settings['recaptcha_secret_key'] === ''))
            || ($provider === 'turnstile' && ($settings['turnstile_site_key'] === '' || $settings['turnstile_secret_key'] === ''));
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Kommentarsicherheit', 'restatify-base'); ?></h1>
            <?php if ($keys_missing) : ?>
                <div class="notice notice-error"><p><?php esc_html_e('Beim gewählten CAPTCHA-Anbieter fehlt ein Site Key oder Secret Key. Kommentare werden abgelehnt, bis beide Schlüssel eingetragen sind.', 'restatify-base'); ?></p></div>
            <?php endif; ?>
            <?php if ($provider !== 'none' && !class_exists('\\Restatify\\Shared\\Security\\CaptchaVerifier')) : ?>
                <div class="notice notice-error"><p><?php esc_html_e('Die gemeinsame CAPTCHA-Prüfung ist nicht verfügbar. Installiere die vom Theme benötigte Shared-Library-Version; Kommentare bleiben bis dahin gesperrt.', 'restatify-base'); ?></p></div>
            <?php endif; ?>
            <form action="options.php" method="post">
                <?php
                settings_fields(self::SETTINGS_GROUP);
                do_settings_sections(self::PAGE_SLUG);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render explanatory settings text.
     */
    public static function render_settings_intro(): void {
        echo '<p>' . esc_html__('Für öffentliche Kommentare deaktiviere unter Einstellungen > Diskussion die Registrierungspflicht und aktiviere die Pflichtfelder für Name und E-Mail-Adresse.', 'restatify-base') . '</p>';
        echo '<p>' . esc_html__('Wähle einen CAPTCHA-Anbieter, kombiniere ihn bei Bedarf mit dem Honeypot und speichere die Schlüssel hier. Bei fehlenden Schlüsseln oder fehlgeschlagener Prüfung werden Kommentare abgelehnt.', 'restatify-base') . '</p>';
        echo '<p>' . esc_html__('Wenn Google reCAPTCHA oder Cloudflare Turnstile aktiv ist, erhält der Anbieter Prüfdaten wie die IP-Adresse des Besuchers. Ergänze deine Datenschutzhinweise entsprechend.', 'restatify-base') . '</p>';
    }

    /**
     * Render the honeypot checkbox.
     */
    public static function render_honeypot_setting(): void {
        $settings = self::settings();
        printf(
            '<label><input type="checkbox" name="%1$s[honeypot]" value="1" %2$s> %3$s</label>',
            esc_attr(self::OPTION_NAME),
            checked($settings['honeypot'], true, false),
            esc_html__('Verstecktes Honeypot-Feld aktivieren', 'restatify-base')
        );
    }

    /**
     * Render the CAPTCHA provider selector.
     */
    public static function render_provider_setting(): void {
        $settings = self::settings();
        $providers = [
            'none' => __('Keiner', 'restatify-base'),
            'recaptcha' => __('Google reCAPTCHA v3', 'restatify-base'),
            'turnstile' => __('Cloudflare Turnstile', 'restatify-base'),
        ];
        printf('<select name="%1$s[captcha_provider]" id="restatify-comment-captcha-provider">', esc_attr(self::OPTION_NAME));
        foreach ($providers as $value => $label) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr($value),
                selected($settings['captcha_provider'], $value, false),
                esc_html($label)
            );
        }
        echo '</select>';
    }

    /**
     * Render reCAPTCHA keys.
     */
    public static function render_recaptcha_keys_setting(): void {
        $settings = self::settings();
        self::render_key_input('recaptcha_site_key', __('Site Key', 'restatify-base'), $settings['recaptcha_site_key'], 'text');
        self::render_secret_input('recaptcha_secret_key', 'clear_recaptcha_secret', __('Secret Key', 'restatify-base'), $settings['recaptcha_secret_key'] !== '');
    }

    /**
     * Render Turnstile keys.
     */
    public static function render_turnstile_keys_setting(): void {
        $settings = self::settings();
        self::render_key_input('turnstile_site_key', __('Site Key', 'restatify-base'), $settings['turnstile_site_key'], 'text');
        self::render_secret_input('turnstile_secret_key', 'clear_turnstile_secret', __('Secret Key', 'restatify-base'), $settings['turnstile_secret_key'] !== '');
    }

    /**
     * Return sanitized settings with safe defaults.
     *
     * @return array<string,mixed>
     */
    private static function settings(): array {
        $saved = get_option(self::OPTION_NAME, []);
        $settings = array_merge(self::defaults(), is_array($saved) ? $saved : []);
        if (!in_array($settings['captcha_provider'], ['none', 'recaptcha', 'turnstile'], true)) {
            $settings['captcha_provider'] = 'none';
        }

        foreach (['recaptcha_site_key', 'recaptcha_secret_key', 'turnstile_site_key', 'turnstile_secret_key'] as $key) {
            $settings[$key] = is_string($settings[$key]) ? $settings[$key] : '';
        }

        $settings['honeypot'] = (bool) $settings['honeypot'];
        return $settings;
    }

    /**
     * Default comment-protection settings.
     *
     * @return array<string,mixed>
     */
    private static function defaults(): array {
        return [
            'honeypot' => true,
            'captcha_provider' => 'none',
            'recaptcha_site_key' => '',
            'recaptcha_secret_key' => '',
            'turnstile_site_key' => '',
            'turnstile_secret_key' => '',
        ];
    }

    /**
     * Sanitize a submitted public key, retaining its previous value if omitted.
     *
     * @param array<string,mixed> $input Submitted settings.
     */
    private static function sanitize_submitted_text(array $input, string $key, string $saved_value): string {
        $value = $input[$key] ?? $saved_value;
        return is_string($value) ? sanitize_text_field($value) : $saved_value;
    }

    /**
     * Preserve an existing secret when the password field is left blank.
     *
     * @param array<string,mixed> $input Submitted settings.
     * @param array<string,mixed> $saved Previously saved settings.
     */
    private static function submitted_secret(array $input, string $key, array $saved): string {
        $raw = $input[$key] ?? '';
        $submitted = is_string($raw) ? trim($raw) : '';
        return $submitted !== '' ? sanitize_text_field($submitted) : (string) ($saved[$key] ?? '');
    }

    /**
     * Read and sanitize a scalar field from the current comment request.
     */
    private static function posted_text(string $key): string {
        $value = $_POST[$key] ?? '';
        if (!is_string($value)) {
            return '';
        }

        return sanitize_text_field(wp_unslash($value));
    }

    /**
     * Render a public site-key input.
     *
     * @param string $type Input type.
     */
    private static function render_key_input(string $key, string $label, string $value, string $type): void {
        printf(
            '<p><label>%1$s<br><input class="regular-text" type="%2$s" name="%3$s[%4$s]" value="%5$s" autocomplete="off"></label></p>',
            esc_html($label),
            esc_attr($type),
            esc_attr(self::OPTION_NAME),
            esc_attr($key),
            esc_attr($value)
        );
    }

    /**
     * Render a write-only secret field and an explicit clear checkbox.
     */
    private static function render_secret_input(string $key, string $clear_key, string $label, bool $is_configured): void {
        printf(
            '<p><label>%1$s<br><input class="regular-text" type="password" name="%2$s[%3$s]" value="" autocomplete="new-password" placeholder="%4$s"></label></p>',
            esc_html($label),
            esc_attr(self::OPTION_NAME),
            esc_attr($key),
            esc_attr($is_configured ? __('Eingerichtet; leer lassen, um den Schlüssel beizubehalten', 'restatify-base') : __('Nicht eingerichtet', 'restatify-base'))
        );
        printf(
            '<label><input type="checkbox" name="%1$s[%2$s]" value="1"> %3$s</label>',
            esc_attr(self::OPTION_NAME),
            esc_attr($clear_key),
            esc_html__('Gespeicherten Secret Key löschen', 'restatify-base')
        );
    }

    /**
     * Stop comment insertion when the anti-spam check fails.
     */
    private static function reject_comment(): void {
        $message = self::translate(__('Die Sicherheitsprüfung konnte nicht abgeschlossen werden. Bitte versuche es erneut.', 'restatify-base'));
        wp_die(
            esc_html($message),
            esc_html__('Kommentar nicht gesendet', 'restatify-base'),
            ['response' => 400]
        );
    }

    /**
     * Resolve a fixed theme string through Polylang when available.
     */
    private static function translate(string $text): string {
        return function_exists('pll__') ? (string) pll__($text) : $text;
    }
}

Restatify_Comment_Security::init();
