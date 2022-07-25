<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @covers \App\Controller\DefaultController
 * @covers \App\Service\DirectoryListing
 */
class DefaultControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider imageProvider
     */
    public function testLoadImage(string $image)
    {
        $this->client->request('GET', $image);

        $expiresHeader = $this->client->getResponse()->headers->get('expires');
        $expires = (new \DateTime($expiresHeader))->getTimestamp();

        $this->assertResponseIsSuccessful();
        $this->assertLessThanOrEqual((new \DateTime('+1 week'))->getTimestamp(), $expires);
    }

    public function testAccessNonExistingDirectory()
    {
        $target = rawurlencode('/non-existing-directory');
        $this->client->request('GET', '/explore?path='.$target);

        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * @dataProvider targetPathProvider
     * @covers \App\Twig\AppExtension::renderBreadcrumbs
     */
    public function testCanAccessTargetPath(string $target)
    {
        $this->client->request('GET', $target);

        $this->assertResponseIsSuccessful();
    }

    public function targetPathProvider(): array
    {
        return [
            ['/explore'],
            ['/explore?path='.rawurlencode('/Series 1')],
            ['/explore?path='.rawurlencode('/Series 1/Chapter 001')],
        ];
    }

    public function imageProvider(): array
    {
        return [
            ['explore?path=image.jpg'],
            ['explore?path=image.jpeg'],
            ['explore?path=image.png'],
            ['explore?path=image.webp'],
        ];
    }
}
