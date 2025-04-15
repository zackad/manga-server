<?php

declare(strict_types=1);

namespace App\Tests\Twig;

use App\Twig\AppExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

#[\PHPUnit\Framework\Attributes\CoversClass(AppExtension::class)]
class AppExtensionTest extends TestCase
{
    private $requestStack;
    private $twig;
    private $urlGenerator;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = $this->createStub(UrlGeneratorInterface::class);
    }

    public function testMakeSureIsCovered()
    {
        $extension = new AppExtension($this->requestStack, $this->twig, $this->urlGenerator);

        $this->assertIsArray($extension->getFilters());
        $this->assertIsArray($extension->getFunctions());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dataProvider')]
    public function testIsImage(string $filename, bool $result)
    {
        $extension = new AppExtension($this->requestStack, $this->twig, $this->urlGenerator);

        $this->assertEquals($result, $extension->isImage($filename));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('uriProvider')]
    public function testGetTitleFromUri(string $input, ?string $output)
    {
        $mainRequest = $this->createStub(Request::class);
        $query = new InputBag(['path' => $input]);

        $mainRequest->query = $query;
        $this->requestStack->method('getMainRequest')->willReturn($mainRequest);
        $extension = new AppExtension($this->requestStack, $this->twig, $this->urlGenerator);

        $this->assertEquals($output, $extension->getTitleFromUri());
    }

    public function testRenderBreadcrumbsReturnNullWhenRequestObjectIsNull()
    {
        $extension = new AppExtension($this->requestStack, $this->twig, $this->urlGenerator);

        $this->assertNull($extension->renderBreadcrumbs());
    }

    public function testAutowiring()
    {
        $requestStack = $this->createMock(RequestStack::class);
        $extension = new AppExtension($requestStack, $this->twig, $this->urlGenerator);

        $this->assertEquals(null, $extension->getTitleFromUri());
    }

    public static function uriProvider(): array
    {
        return [
            ['', null],
            ['string', 'string'],
            ['/string/title', 'title'],
            ['/string/title/other', 'other'],
            ['/string/title/other/', 'other'],
        ];
    }

    public static function dataProvider(): array
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
