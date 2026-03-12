<?php
/**
 * Customizer module loader.
 */

$restatify_customizer_modules = [
    '/inc/customizer/helpers.php',
    '/inc/customizer/register.php',
    '/inc/customizer/typography.php',
    '/inc/customizer/icons.php',
];

foreach ($restatify_customizer_modules as $restatify_customizer_module) {
    $restatify_customizer_path = get_template_directory() . $restatify_customizer_module;
    if (file_exists($restatify_customizer_path)) {
        require_once $restatify_customizer_path;
    }
}
