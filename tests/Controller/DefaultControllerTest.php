<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider imageProvider
     */
    public function testLoadImage($image)
    {
        $this->client->request('GET', $image);

        $expiresHeader = $this->client->getResponse()->headers->get('expires');
        $expires = (new \DateTime($expiresHeader))->getTimestamp();

        $this->assertResponseIsSuccessful();
        $this->assertLessThanOrEqual((new \DateTime('+1 week'))->getTimestamp(), $expires);
    }

    public function testAccessNonExistingDirectory()
    {
        $this->client->request('GET', '/non-existing-directory');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testAccessHomepage()
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function imageProvider()
    {
        return [
            ['/image.jpg'],
            ['/image.jpeg'],
            ['/image.png'],
            ['/image.webp'],
        ];
    }
}
