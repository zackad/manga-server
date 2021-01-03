<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
class NextChapterTest extends WebTestCase
{
    public function testGetNextChapter()
    {
        $client = static::createClient();
        $client->request('GET', 'Series%201%2FChapter%20001?next');
        $this->assertResponseRedirects('%2FSeries%201%2FChapter%20002');
        $client->request('GET', 'Series%201%2FChapter%20005?next');
        $this->assertResponseRedirects('%2FSeries%201');
    }
}
