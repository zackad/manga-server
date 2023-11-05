<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\DefaultController
 * @covers \App\Controller\ExploreController
 * @covers \App\Service\DirectoryListing
 */
class ExploreControllerTest extends WebTestCase
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
     *
     * @covers \App\Twig\AppExtension::renderBreadcrumbs
     */
    public function testCanAccessTargetPath(string $target)
    {
        $this->client->request('GET', $target);

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider redirectDataProvider
     */
    public function testRedirectToArchiveController(array $queryParams, string $targetUrl)
    {
        $this->client->request('GET', '/explore', $queryParams);
        self::assertResponseRedirects($targetUrl);
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

    public function redirectDataProvider(): array
    {
        return [
            [['path' => 'archive.zip'], '/archive/archive.zip'],
            [['path' => 'archive.ZIP'], '/archive/archive.ZIP'],
            [['path' => 'archive.EPUB'], '/archive/archive.EPUB'],
            [['path' => 'archive.epub'], '/archive/archive.epub'],
            [['path' => 'archive.cbz'], '/archive/archive.cbz'],
            [['path' => 'archive.CBZ'], '/archive/archive.CBZ'],
        ];
    }
}
