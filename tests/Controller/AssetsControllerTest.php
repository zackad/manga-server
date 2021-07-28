<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
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

        switch (pathinfo($asset, PATHINFO_EXTENSION)) {
            case 'css':
                $this->assertResponseHeaderSame('Content-Type', 'text/css');

                break;

            case 'js':
                $this->assertResponseHeaderSame('Content-Type', 'application/javascript');

                break;

            default:
                break;
        }

        $expiresHeader = $this->client->getResponse()->headers->get('expires');
        $expires = (new \DateTime($expiresHeader))->getTimestamp();
        $this->assertLessThanOrEqual((new \DateTime('+1 week'))->getTimestamp(), $expires);
    }

    public function testGetNonExistingFile()
    {
        $this->client->request('GET', '/build/non-existing.json');
        $this->assertResponseStatusCodeSame(404);
    }

    public function assetsProvider()
    {
        $manifestPath = dirname(dirname(__DIR__)).'/public/build/manifest.json';
        $data = json_decode(file_get_contents($manifestPath), true);

        return [array_values($data)];
    }
}
