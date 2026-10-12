<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2) . '/inc/blocks.php';

final class BackgroundVideoAttributesTest extends TestCase {
    public function testEveryCustomBackgroundBlockGetsTheSameServerSchema(): void {
        $files = glob(dirname(__DIR__, 2) . '/src/*/block.json');
        $count = 0;
        foreach ($files as $file) {
            $metadata = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
            if (!isset($metadata['attributes']['backgroundImageEnabled'])) {
                continue;
            }
            $args = restatify_background_video_attributes($metadata, $metadata['name']);
            self::assertSame(['type' => 'string', 'default' => 'image'], $args['attributes']['backgroundMediaType']);
            self::assertSame(['type' => 'string', 'default' => ''], $args['attributes']['backgroundVideoUrl']);
            self::assertSame($metadata['attributes']['backgroundImageEnabled'], $args['attributes']['backgroundImageEnabled']);
            $count++;
        }
        self::assertSame(14, $count);
    }

    public function testCoreAndUnrelatedBlocksAreUnaffected(): void {
        $core = ['attributes' => ['backgroundImageEnabled' => ['type' => 'boolean']]];
        self::assertSame($core, restatify_background_video_attributes($core, 'core/cover'));
        self::assertSame([], restatify_background_video_attributes([], 'restatify/blog-navigation'));
    }
}
