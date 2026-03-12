<?php

$restatify_includes = [
    '/inc/theme-setup.php',
    '/inc/assets.php',
    '/inc/navigation-walker.php',
    '/inc/customizer.php',
    '/inc/polylang.php',
];

foreach ($restatify_includes as $restatify_file) {
    $restatify_path = get_template_directory() . $restatify_file;
    if (file_exists($restatify_path)) {
        require_once $restatify_path;
    }
}
