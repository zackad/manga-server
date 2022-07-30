<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Service\NextChapterResolver;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @internal
 * @covers \App\Controller\DefaultController
 * @covers \App\Controller\ExploreController
 * @covers \App\Service\DirectoryListing
 * @covers \App\Service\NextChapterResolver
 */
class NextChapterTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider nextDataProvider
     */
    public function testGetNextChapter(array $queryParams, string $redirectTarget)
    {
        $this->client->request('GET', '/explore', $queryParams);
        $this->assertResponseRedirects($redirectTarget);
    }

    public function nextDataProvider(): array
    {
        return [
            [['next' => ''], '/explore'],
            [['path' => urlencode('empty-directory'), 'next' => ''], '/explore'],
            [['path' => rawurlencode('Series 1/Chapter 001'), 'next' => ''], sprintf('/explore?path=%s', '/Series%201/Chapter%20002')],
            [['path' => rawurlencode('Series 1/Chapter 005'), 'next' => ''], sprintf('/explore?path=%s', '/Series%201')],
        ];
    }

    public function testHomeDoesNotRedirect()
    {
        $this->client->request('GET', '?next');
        $this->assertResponseRedirects('/explore');
    }

    public function testRequestObjectIsNullReturnToHomepage()
    {
        $requestStack = $this->createMock(RequestStack::class);
        $urlGenerator = $this->createStub(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/');
        $nextResolver = new NextChapterResolver('/path/to/manga/root', $requestStack, $urlGenerator);

        $this->assertEquals('/', $nextResolver->resolve());
    }
}
