<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2) . '/inc/shared-library.php';

final class SharedLibraryTest extends TestCase {
    private string $root;

    protected function setUp(): void {
        $this->root = sys_get_temp_dir() . '/restatify-shared-' . bin2hex(random_bytes(8));
        mkdir($this->root . '/payload/src/php/Runtime', 0755, true);
        mkdir($this->root . '/payload/src/php/Security', 0755, true);
        file_put_contents($this->root . '/payload/src/php/Runtime/PluginState.php', '<?php // runtime');
        file_put_contents($this->root . '/payload/src/php/Security/CaptchaVerifier.php', '<?php // verifier');
    }

    protected function tearDown(): void {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->root, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
        }
        rmdir($this->root);
    }

    public function testThemeOnlyInstallCopiesExactVersionAndPreservesOlderVersions(): void {
        $target = $this->root . '/plugins/wp_restatify-shared/versions/1.1.0';
        $older = dirname($target) . '/1.0.2';
        mkdir($older, 0755, true);
        file_put_contents($older . '/keep.txt', 'keep');
        restatify_theme_install_shared_payload($this->root . '/payload', $target);
        self::assertFileExists($target . '/src/php/Security/CaptchaVerifier.php');
        self::assertFileExists($target . '/src/php/Runtime/PluginState.php');
        self::assertSame('keep', file_get_contents($older . '/keep.txt'));
        // A complete installation does not need a packaged payload on later requests.
        restatify_theme_install_shared_payload($this->root . '/absent', $target);
    }

    public function testRepairsMissingRequiredFileWithoutOverwritingInstalledFiles(): void {
        $target = $this->root . '/installed';
        mkdir($target . '/src/php/Runtime', 0755, true);
        file_put_contents($target . '/src/php/Runtime/PluginState.php', 'existing');
        restatify_theme_install_shared_payload($this->root . '/payload', $target);
        self::assertSame('existing', file_get_contents($target . '/src/php/Runtime/PluginState.php'));
        self::assertFileExists($target . '/src/php/Security/CaptchaVerifier.php');
    }

    public function testMissingPayloadFailsExplicitly(): void {
        $this->expectException(RuntimeException::class);
        restatify_theme_install_shared_payload($this->root . '/absent', $this->root . '/installed');
    }
}
