<?php

namespace Versatecnologia\Cadsus\Tests\Architecture;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class CoreIndependenceTest extends TestCase
{
    public function testCoreDoesNotDependOnLaravelOrApplicationModels(): void
    {
        // O service provider é o único ponto de integração com Laravel e é opcional.
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__DIR__, 2) . '/src'));

        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php' || $file->getFilename() === 'CadsusClientServiceProvider.php') {
                continue;
            }

            $contents = file_get_contents($file->getPathname());
            self::assertStringNotContainsString('Illuminate\\', $contents, $file->getPathname());
            self::assertStringNotContainsString('App\\Models\\', $contents, $file->getPathname());
        }
    }
}
