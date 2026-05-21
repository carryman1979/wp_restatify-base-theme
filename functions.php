<?php

require_once get_template_directory() . '/inc/shared-runtime.php';
if (function_exists('restatify_theme_shared_bootstrap')) {
    restatify_theme_shared_bootstrap();
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
