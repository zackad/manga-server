<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Service\NextChapterResolver;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @internal
 * @covers \App\Controller\DefaultController
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
//        $this->client->request('GET', 'Series%201%2FChapter%20001?next');
//        $this->assertResponseRedirects('%2FSeries%201%2FChapter%20002');
//        $this->client->request('GET', 'Series%201%2FChapter%20005?next');
//        $this->assertResponseRedirects('%2FSeries%201');
        $this->client->request('GET', '/explore', $queryParams);
        $this->assertResponseRedirects($redirectTarget);
    }

    public function nextDataProvider(): array
    {
        return [
            [['path' => rawurlencode('empty-directory'), 'next' => ''], '/explore'],
            [['path' => rawurlencode('Series 1/Chapter 01'), 'next' => ''], '/'],
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
        $nextResolver = new NextChapterResolver('/path/to/manga/root', $requestStack);

        $this->assertEquals('/', $nextResolver->resolve());
    }
}
