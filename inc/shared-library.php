<?php
/**
 * Install the exact bundled shared runtime without requiring an active plugin.
 * Local development source selection remains the responsibility of functions.php.
 */
function restatify_theme_install_shared_payload(string $source, string $target): void {
    $required = ['Runtime/PluginState.php', 'Security/CaptchaVerifier.php'];
    $missing = array_filter($required, static function (string $file) use ($target): bool {
        return !is_file($target . '/src/php/' . $file);
    });
    if (!$missing) {
        return;
    }

    foreach ($required as $file) {
        if (!is_file($source . '/src/php/' . $file)) {
            throw new RuntimeException('Missing bundled Restatify shared runtime: ' . $file);
        }
    }

    $sourcePhp = $source . '/src/php';
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourcePhp, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($files as $file) {
        if (!$file->isFile()) {
            continue;
        }
        $relative = substr($file->getPathname(), strlen($sourcePhp) + 1);
        $destination = $target . '/src/php/' . $relative;
        if (is_file($destination)) {
            continue;
        }
        $directory = dirname($destination);
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('Cannot create the Restatify shared runtime directory.');
        }
        if (!copy($file->getPathname(), $destination)) {
            throw new RuntimeException('Cannot install the bundled Restatify shared runtime.');
        }
    }
}
