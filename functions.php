<?php

if (!defined('RESTATIFY_BASE_THEME_SHARED_VERSION')) {
    define('RESTATIFY_BASE_THEME_SHARED_VERSION', '1.0.2');
}

$restatify_theme_require_first = static function (array $paths): bool {
    foreach ($paths as $path) {
        if (is_string($path) && $path !== '' && file_exists($path)) {
            require_once $path;
            return true;
        }
    }

    return false;
};

$restatify_theme_local_shared_root = dirname(get_template_directory(), 3) . '/wp_restatify-shared';
$restatify_theme_use_local_latest_shared = is_dir($restatify_theme_local_shared_root . '/src/php');
$restatify_theme_shared_base_path = '';

if ($restatify_theme_use_local_latest_shared) {
    $restatify_theme_shared_base_path = $restatify_theme_local_shared_root;
} else {
    $restatify_theme_versioned_roots = [];
    if (defined('WP_PLUGIN_DIR') && is_string(WP_PLUGIN_DIR) && WP_PLUGIN_DIR !== '') {
        $restatify_theme_versioned_roots[] = WP_PLUGIN_DIR . '/wp_restatify-shared';
    }
    if (defined('WPMU_PLUGIN_DIR') && is_string(WPMU_PLUGIN_DIR) && WPMU_PLUGIN_DIR !== '') {
        $restatify_theme_versioned_roots[] = WPMU_PLUGIN_DIR . '/wp_restatify-shared';
    }

    foreach ($restatify_theme_versioned_roots as $restatify_theme_root) {
        $restatify_theme_versioned_path = rtrim($restatify_theme_root, '/') . '/versions/' . RESTATIFY_BASE_THEME_SHARED_VERSION;
        if (is_dir($restatify_theme_versioned_path . '/src/php')) {
            $restatify_theme_shared_base_path = $restatify_theme_versioned_path;
            break;
        }
    }

    if ($restatify_theme_shared_base_path === '' && count($restatify_theme_versioned_roots) > 0) {
        $restatify_theme_shared_base_path = rtrim($restatify_theme_versioned_roots[0], '/') . '/versions/' . RESTATIFY_BASE_THEME_SHARED_VERSION;
    }
}

if (!class_exists('\\Restatify\\Shared\\Runtime\\PluginState', false)) {
    $restatify_theme_require_first([
        rtrim($restatify_theme_shared_base_path, '/') . '/src/php/Runtime/PluginState.php',
    ]);
}

$restatify_includes = [
    '/inc/theme-setup.php',
    '/inc/assets.php',
    '/inc/maintenance-layout.php',
    '/inc/navigation-walker.php',
    '/inc/icons-admin.php',
    '/inc/customizer.php',
    '/inc/polylang.php',
    '/inc/blocks.php', // Block-Registrierung
];

foreach ($restatify_includes as $restatify_file) {
    $restatify_path = get_template_directory() . $restatify_file;
    if (file_exists($restatify_path)) {
        require_once $restatify_path;
    }
}
