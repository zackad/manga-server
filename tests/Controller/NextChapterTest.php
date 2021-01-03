<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
class NextChapterTest extends WebTestCase
{
    private KernelBrowser $client;

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
    }

    public function testHomeDoesNotRedirect()
    {
        $this->client->request('GET', '?next');
        $this->assertResponseRedirects('%2F');
    }
}
