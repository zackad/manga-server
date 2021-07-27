<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @covers \App\Controller\DefaultController
 * @covers \App\Service\ComicBook
 * @covers \App\Service\DirectoryListing
 * @covers \App\Service\NextChapterResolver
 * @covers \App\Service\PathTool
 */
class NextChapterTest extends WebTestCase
{
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
}
