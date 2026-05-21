<?php

$restatify_theme_require_first = static function (array $paths): bool {
    foreach ($paths as $path) {
        if (is_string($path) && $path !== '' && file_exists($path)) {
            require_once $path;
            return true;
        }
    }

    return false;
};

$restatify_theme_require_first([
    dirname(get_template_directory(), 3) . '/wp_restatify-shared/src/php/Runtime/PluginState.php',
]);

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
