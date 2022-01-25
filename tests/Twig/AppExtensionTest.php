<?php

declare(strict_types=1);

namespace App\Tests\Twig;

use App\Twig\AppExtension;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Twig\AppExtension
 */
class AppExtensionTest extends TestCase
{
    public function testMakeSureIsCovered()
    {
        $extension = new AppExtension();

        $this->assertIsArray($extension->getFilters());
        $this->assertIsArray($extension->getFunctions());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testIsImage(string $filename, bool $result)
    {
        $extension = new AppExtension();

        $this->assertEquals($result, $extension->isImage($filename));
    }

    public function dataProvider(): array
    {
        return [
            ['image.pny', false],
            ['image.pnG', true],
            ['image.jpeg', true],
            ['image.jpg', true],
            ['image.JPG', true],
            ['image.webb', false],
            ['image.webp', true],
            ['image.gif', false],
        ];
    }
}
