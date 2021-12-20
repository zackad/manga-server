<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @covers \App\Controller\SearchController
 * @covers \App\Service\Search
 */
class SearchControllerTest extends WebTestCase
{
    public function testSearchWithEmptyStringShouldReturnNotFoundResponse()
    {
        $client = static::createClient();
        $client->request('GET', '/search', ['q' => '']);

        $this->assertResponseStatusCodeSame(404);
    }
}
