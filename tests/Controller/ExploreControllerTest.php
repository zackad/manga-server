<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\App\Controller\DefaultController::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\App\Controller\ExploreController::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\App\Service\DirectoryListing::class)]
#[\PHPUnit\Framework\Attributes\CoversMethod(\App\Twig\AppExtension::class, 'renderBreadcrumbs')]
class ExploreControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('imageProvider')]
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

    #[\PHPUnit\Framework\Attributes\DataProvider('targetPathProvider')]
    public function testCanAccessTargetPath(string $target)
    {
        $this->client->request('GET', $target);

        $this->assertResponseIsSuccessful();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('redirectDataProvider')]
    public function testRedirectToArchiveController(array $queryParams, string $targetUrl)
    {
        $this->client->request('GET', '/explore', $queryParams);
        self::assertResponseRedirects($targetUrl);
    }

    public static function targetPathProvider(): array
    {
        return [
            ['/explore'],
            ['/explore?path='.rawurlencode('/Series 1')],
            ['/explore?path='.rawurlencode('/Series 1/Chapter 001')],
        ];
    }

    public static function imageProvider(): array
    {
        return [
            ['explore?path=image.jpg'],
            ['explore?path=image.jpeg'],
            ['explore?path=image.png'],
            ['explore?path=image.webp'],
        ];
    }

    public static function redirectDataProvider(): array
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
