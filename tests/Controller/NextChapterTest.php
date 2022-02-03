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
 * @covers \App\Service\PathTool
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

    public function testGetNextChapter()
    {
        $this->client->request('GET', 'Series%201%2FChapter%20001?next');
        $this->assertResponseRedirects('%2FSeries%201%2FChapter%20002');
        $this->client->request('GET', 'Series%201%2FChapter%20005?next');
        $this->assertResponseRedirects('%2FSeries%201');
        $this->client->request('GET', 'empty-directory?next');
        $this->assertResponseRedirects('/');
    }

    public function testHomeDoesNotRedirect()
    {
        $this->client->request('GET', '?next');
        $this->assertResponseRedirects('/');
    }

    public function testRequestObjectIsNullReturnToHomepage()
    {
        $requestStack = $this->createMock(RequestStack::class);
        $nextResolver = new NextChapterResolver('/path/to/manga/root', $requestStack);

        $this->assertEquals('/', $nextResolver->resolve());
    }
}
