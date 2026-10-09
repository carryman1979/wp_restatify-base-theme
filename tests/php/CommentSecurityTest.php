<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CommentSecurityTest extends TestCase {
    protected function setUp(): void {
        parent::setUp();
        $GLOBALS['restatify_shared_test_options'] = [];
        $GLOBALS['restatify_shared_http_calls'] = [];
        $GLOBALS['restatify_shared_http_response'] = [
            'response' => ['code' => 200],
            'body' => '{"success":true,"score":0.9,"action":"restatify_comment"}',
        ];
        $GLOBALS['restatify_comment_test_post_type'] = 'post';
        $_POST = [];
        $_SERVER = [];
    }

    public function testSanitizationPreservesSecretsAndAllowsExplicitClearing(): void {
        $GLOBALS['restatify_shared_test_options']['restatify_comment_security'] = [
            'recaptcha_secret_key' => 'saved-secret',
        ];

        $settings = Restatify_Comment_Security::sanitize_settings([
            'captcha_provider' => 'recaptcha',
            'honeypot' => '1',
            'recaptcha_site_key' => 'public-key',
        ]);

        self::assertSame('recaptcha', $settings['captcha_provider']);
        self::assertSame('public-key', $settings['recaptcha_site_key']);
        self::assertSame('saved-secret', $settings['recaptcha_secret_key']);

        $cleared = Restatify_Comment_Security::sanitize_settings([
            'captcha_provider' => 'none',
            'clear_recaptcha_secret' => '1',
        ]);
        self::assertSame('', $cleared['recaptcha_secret_key']);
    }

    public function testHoneypotAllowsEmptyValuesAndRejectsFilledValues(): void {
        $comment = ['comment_post_ID' => 10, 'comment_type' => 'comment'];
        self::assertSame($comment, Restatify_Comment_Security::validate_comment($comment));

        $_POST['restatify_comment_website'] = 'bot text';
        $this->expectException(RuntimeException::class);
        Restatify_Comment_Security::validate_comment($comment);
    }

    public function testRecaptchaRequiresConfiguredKeysAndValidTokenAction(): void {
        $GLOBALS['restatify_shared_test_options']['restatify_comment_security'] = [
            'honeypot' => true,
            'captcha_provider' => 'recaptcha',
            'recaptcha_site_key' => 'site-key',
            'recaptcha_secret_key' => 'secret-key',
        ];
        $_POST['restatify_comment_captcha_token'] = 'valid-token';

        $comment = ['comment_post_ID' => 10, 'comment_type' => 'comment'];
        self::assertSame($comment, Restatify_Comment_Security::validate_comment($comment));
        self::assertSame(
            'https://www.google.com/recaptcha/api/siteverify',
            $GLOBALS['restatify_shared_http_calls'][0][0]
        );

        $GLOBALS['restatify_shared_http_response']['body'] = '{"success":true,"score":0.9,"action":"wrong"}';
        $this->expectException(RuntimeException::class);
        Restatify_Comment_Security::validate_comment($comment);
    }

    public function testUnavailableCaptchaVerifierBlocksCommentsButNonPostCommentsAreUnaffected(): void {
        $GLOBALS['restatify_shared_test_options']['restatify_comment_security'] = [
            'honeypot' => true,
            'captcha_provider' => 'turnstile',
            'turnstile_site_key' => '',
            'turnstile_secret_key' => '',
        ];
        $GLOBALS['restatify_comment_test_post_type'] = 'page';
        $comment = ['comment_post_ID' => 10, 'comment_type' => 'comment'];

        self::assertSame($comment, Restatify_Comment_Security::validate_comment($comment));

        $GLOBALS['restatify_comment_test_post_type'] = 'post';
        $this->expectException(RuntimeException::class);
        Restatify_Comment_Security::validate_comment($comment);
    }

    public function testPingbacksAndTrackbacksDoNotRequireCommentFormCaptcha(): void {
        $GLOBALS['restatify_shared_test_options']['restatify_comment_security'] = [
            'honeypot' => true,
            'captcha_provider' => 'recaptcha',
            'recaptcha_site_key' => '',
            'recaptcha_secret_key' => '',
        ];
        $pingback = ['comment_post_ID' => 10, 'comment_type' => 'pingback'];

        self::assertSame($pingback, Restatify_Comment_Security::validate_comment($pingback));
    }
}
