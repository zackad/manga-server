<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 *
 * @covers \App\Controller\AssetsController
 */
class AssetsControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider assetsProvider
     *
     * @param mixed $asset
     */
    public function testGetHeaderOfAssetsFile($asset)
    {
        $this->client->request('GET', $asset);
        $this->assertResponseIsSuccessful();

        $expiresHeader = $this->client->getResponse()->headers->get('expires');
        $expires = (new \DateTime($expiresHeader))->getTimestamp();
        $this->assertLessThanOrEqual((new \DateTime('+1 week'))->getTimestamp(), $expires);
    }

    public function testGetNonExistingFile()
    {
        $this->client->request('GET', '/build/non-existing.json');
        $this->assertResponseStatusCodeSame(404);
    }

    public function assetsProvider(): array
    {
        $manifestPath = dirname(__DIR__, 2).'/public/build/manifest.json';
        $data = json_decode(file_get_contents($manifestPath), true);
        $mappedValues = array_map(function ($value) {
            return [$value];
        }, $data);

        return array_combine(array_keys($data), array_values($mappedValues));
    }
}
