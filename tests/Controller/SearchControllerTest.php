<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\App\Controller\SearchController::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\App\Service\Search::class)]
class SearchControllerTest extends WebTestCase
{
    public function testSearchWithEmptyStringShouldReturnNotFoundResponse()
    {
        $client = static::createClient();
        $client->request('GET', '/search', ['q' => '']);

        $this->assertResponseStatusCodeSame(404);
    }
}
