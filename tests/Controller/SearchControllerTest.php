<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    public function testSearchWithEmptyStringShouldReturnNotFoundResponse()
    {
        $client = static::createClient();
        $client->request('GET', '/search', ['q' => '']);

        $this->assertResponseStatusCodeSame(404);
    }
}
