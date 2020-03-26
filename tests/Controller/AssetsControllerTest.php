<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssetsControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider assetsProvider
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
        $this->assertResponseHeaderSame('Expires', (new \DateTime('+1 week'))->format(DATE_RFC7231));
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
