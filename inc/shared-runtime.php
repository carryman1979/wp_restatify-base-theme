<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('RESTATIFY_THEME_SHARED_VERSION')) {
    define('RESTATIFY_THEME_SHARED_VERSION', '1.0.2');
}

if (!function_exists('restatify_theme_shared_base_dir')) {
    function restatify_theme_shared_base_dir(): string {
        $candidates = [
            rtrim(dirname(get_template_directory(), 3), '/\\') . '/wp_restatify-shared',
            rtrim((defined('WP_CONTENT_DIR') ? WP_CONTENT_DIR : dirname(get_template_directory(), 2)), '/\\') . '/wp_restatify-shared',
        ];

        foreach ($candidates as $candidate) {
            if (is_dir($candidate)) {
                return $candidate;
            }
        }

        return $candidates[0];
    }
}

if (!function_exists('restatify_theme_shared_versions_base_dir')) {
    function restatify_theme_shared_versions_base_dir(): string {
        return restatify_theme_shared_base_dir() . '/versions';
    }
}

if (!function_exists('restatify_theme_shared_target_dir')) {
    function restatify_theme_shared_target_dir(): string {
        return restatify_theme_shared_versions_base_dir() . '/' . RESTATIFY_THEME_SHARED_VERSION;
    }
}

if (!function_exists('restatify_theme_shared_packaged_version_dir')) {
    function restatify_theme_shared_packaged_version_dir(): string {
        return get_template_directory() . '/shared-install/wp_restatify-shared/versions/' . RESTATIFY_THEME_SHARED_VERSION;
    }
}

if (!function_exists('restatify_theme_shared_legacy_base_dir')) {
    function restatify_theme_shared_legacy_base_dir(): string {
        return restatify_theme_shared_base_dir();
    }
}

if (!function_exists('restatify_theme_shared_copy_tree')) {
    function restatify_theme_shared_copy_tree(string $source, string $target): bool {
        if (!is_dir($source)) {
            return false;
        }

        if (!is_dir($target) && !wp_mkdir_p($target)) {
            return false;
        }

        $items = scandir($source);
        if (!is_array($items)) {
            return false;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $source_path = $source . '/' . $item;
            $target_path = $target . '/' . $item;

            if (is_dir($source_path)) {
                if (!restatify_theme_shared_copy_tree($source_path, $target_path)) {
                    return false;
                }
                continue;
            }

            if (!@copy($source_path, $target_path)) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('restatify_theme_shared_has_plugin_state')) {
    function restatify_theme_shared_has_plugin_state(string $base_dir): bool {
        return file_exists($base_dir . '/src/php/Runtime/PluginState.php');
    }
}

if (!function_exists('restatify_theme_shared_ensure_installed')) {
    function restatify_theme_shared_ensure_installed(): string {
        $target_dir = restatify_theme_shared_target_dir();
        if (restatify_theme_shared_has_plugin_state($target_dir)) {
            return $target_dir;
        }

        $packaged_dir = restatify_theme_shared_packaged_version_dir();
        if (restatify_theme_shared_has_plugin_state($packaged_dir) && restatify_theme_shared_copy_tree($packaged_dir, $target_dir)) {
            if (restatify_theme_shared_has_plugin_state($target_dir)) {
                return $target_dir;
            }
        }

        $legacy_dir = restatify_theme_shared_legacy_base_dir();
        if (restatify_theme_shared_has_plugin_state($legacy_dir)) {
            return $legacy_dir;
        }

        return $target_dir;
    }
}

if (!function_exists('restatify_theme_shared_bootstrap')) {
    function restatify_theme_shared_bootstrap(): void {
        $shared_root = restatify_theme_shared_ensure_installed();
        $plugin_state_file = $shared_root . '/src/php/Runtime/PluginState.php';

        if (file_exists($plugin_state_file)) {
            require_once $plugin_state_file;
        }
    }
}

add_action('after_switch_theme', 'restatify_theme_shared_bootstrap');
