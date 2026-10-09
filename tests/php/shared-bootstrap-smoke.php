<?php
/**
 * Isolated theme-only bootstrap smoke test; no WordPress plugins or DB required.
 * Optional argument: extracted release theme root.
 */
declare(strict_types=1);

$mode = $argv[2] ?? 'packaged';
$themeSource = $argv[1] ?? dirname(__DIR__, 2);
$sharedSource = getenv('RESTATIFY_SHARED_TEST_ROOT') ?: dirname(__DIR__, 5) . '/wp_restatify-shared';
$sandbox = sys_get_temp_dir() . '/restatify-theme-smoke-' . bin2hex(random_bytes(8));
$theme = $sandbox . '/wp-content/themes/wp_restatify-base-theme';
mkdir($theme . '/inc', 0755, true);
copy($themeSource . '/functions.php', $theme . '/functions.php');
copy($themeSource . '/inc/shared-library.php', $theme . '/inc/shared-library.php');
define('WP_PLUGIN_DIR', $sandbox . '/wp-content/plugins');
define('WPMU_PLUGIN_DIR', $sandbox . '/wp-content/mu-plugins');
function get_template_directory(): string {
    return $GLOBALS['theme'];
}

function copyTree(string $source, string $target): void {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS));
    foreach ($files as $file) {
        if (!$file->isFile()) {
            continue;
        }
        $destination = $target . '/' . substr($file->getPathname(), strlen($source) + 1);
        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }
        copy($file->getPathname(), $destination);
    }
}

try {
    $payload = $themeSource . '/shared-install/wp_restatify-shared/versions/1.1.0/src/php';
    copyTree(is_dir($payload) ? $payload : $sharedSource . '/src/php', $theme . '/shared-install/wp_restatify-shared/versions/1.1.0/src/php');
    $expected = WP_PLUGIN_DIR . '/wp_restatify-shared/versions/1.1.0';
    if ($mode === 'local') {
        $expected = $sandbox . '/wp_restatify-shared';
        copyTree($sharedSource . '/src/php', $expected . '/src/php');
    } elseif ($mode === 'mu') {
        $expected = WPMU_PLUGIN_DIR . '/wp_restatify-shared/versions/1.1.0';
        copyTree($sharedSource . '/src/php', $expected . '/src/php');
    } elseif ($mode !== 'packaged') {
        throw new RuntimeException('Unknown smoke test mode.');
    }
    $older = WP_PLUGIN_DIR . '/wp_restatify-shared/versions/1.0.2';
    mkdir($older, 0755, true);
    file_put_contents($older . '/keep.txt', 'older');
    require $theme . '/functions.php';
    foreach (['Restatify\\Shared\\Runtime\\PluginState', 'Restatify\\Shared\\Security\\CaptchaVerifier'] as $class) {
        if (!class_exists($class, false)) {
            throw new RuntimeException('Shared class was not loaded: ' . $class);
        }
        $loaded = (new ReflectionClass($class))->getFileName();
        if (!str_starts_with(str_replace('\\', '/', $loaded), str_replace('\\', '/', $expected) . '/src/php/')) {
            throw new RuntimeException('Shared runtime loaded from an unexpected source.');
        }
    }
    if (!is_file($older . '/keep.txt')) {
        throw new RuntimeException('An older shared version was removed.');
    }
    if ($mode === 'local' && is_dir(WP_PLUGIN_DIR . '/wp_restatify-shared/versions/1.1.0')) {
        throw new RuntimeException('Local and versioned shared sources were mixed.');
    }
    echo "PASS theme-only shared bootstrap ($mode)\n";
} finally {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sandbox, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $file) {
        $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
    }
    rmdir($sandbox);
}
