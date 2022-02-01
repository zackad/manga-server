<?php

declare(strict_types=1);

namespace App\Tests\Twig;

use App\Twig\AppExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @covers \App\Twig\AppExtension
 */
class AppExtensionTest extends TestCase
{
    private $requestStack;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
    }

    public function testMakeSureIsCovered()
    {
        $extension = new AppExtension($this->requestStack);

        $this->assertIsArray($extension->getFilters());
        $this->assertIsArray($extension->getFunctions());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testIsImage(string $filename, bool $result)
    {
        $extension = new AppExtension($this->requestStack);

        $this->assertEquals($result, $extension->isImage($filename));
    }

    /**
     * @dataProvider uriProvider
     */
    public function testGetTitleFromUri(string $input, ?string $output)
    {
        $mainRequest = $this->createMock(Request::class);
        $mainRequest->method('getRequestUri')->willReturn($input);
        $this->requestStack->method('getMainRequest')->willReturn($mainRequest);
        $extension = new AppExtension($this->requestStack);

        $this->assertEquals($output, $extension->getTitleFromUri($input));
    }

    public function testAutowiring()
    {
        $requestStack = $this->createMock(RequestStack::class);
        $extension = new AppExtension($requestStack);

        $this->assertEquals(null, $extension->getTitleFromUri());
    }

    public function uriProvider(): array
    {
        return [
            ['string', 'string'],
            ['/string/title', 'title'],
            ['/string/title/other', 'other'],
            ['/string/title/other/', 'other'],
        ];
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