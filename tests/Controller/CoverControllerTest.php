<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[\PHPUnit\Framework\Attributes\CoversClass(\App\Controller\CoverController::class)]
class CoverControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $cache = self::getContainer()->get(TagAwareCacheInterface::class);
        $cache->clear();
    }

    public function testGetCoverSuccess()
    {
        $url = '/cover?filename=archive.zip';
        $this->client->request('GET', $url);
        self::assertResponseIsSuccessful();

        // Retrieve existing thumbnail
        $this->client->request('GET', $url);
        self::assertResponseIsSuccessful();
    }

    public function testGetCoverFromEmptyArchiveFailed()
    {
        $url = '/cover?filename=empty.zip';
        $this->client->request('GET', $url);
        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testGetCoverFromNonExistFileShouldFail()
    {
        $url = '/cover?filename=nonexists.zip';
        $this->client->request('GET', $url);
        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
