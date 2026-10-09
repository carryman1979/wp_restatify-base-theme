<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

$sharedRoot = getenv('RESTATIFY_SHARED_TEST_ROOT') ?: dirname(__DIR__, 5) . '/wp_restatify-shared';
require_once $sharedRoot . '/tests/bootstrap.php';

if (!function_exists('add_action')) {
    function add_action(string $hook, callable $callback): void {}
}

if (!function_exists('add_filter')) {
    function add_filter(string $hook, callable $callback, int $priority = 10): void {}
}

if (!function_exists('sanitize_key')) {
    function sanitize_key(string $key): string {
        return strtolower(preg_replace('/[^a-z0-9_\-]/', '', $key) ?? '');
    }
}

if (!function_exists('get_post_type')) {
    function get_post_type(int $postId): string {
        return (string) ($GLOBALS['restatify_comment_test_post_type'] ?? 'post');
    }
}

if (!function_exists('wp_die')) {
    function wp_die(string $message, ...$args): void {
        throw new RuntimeException($message);
    }
}

if (!function_exists('esc_html')) {
    function esc_html(string $text): string {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__(string $text, string $domain = ''): string {
        return esc_html($text);
    }
}

require_once dirname(__DIR__, 2) . '/inc/comment-security.php';
