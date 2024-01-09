<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @internal
 *
 * @covers \App\Controller\DefaultController
 * @covers \App\Controller\ExploreController
 * @covers \App\Service\DirectoryListing
 * @covers \App\Service\NextChapterResolver
 */
class NextChapterTest extends WebTestCase
{
    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = self::getContainer()->get(UrlGeneratorInterface::class);
    }

    public function testHomeDoesNotRedirect()
    {
        $this->client->request('GET', '?next');
        $this->assertResponseRedirects('/explore');
    }

    /**
     * @dataProvider directoryProvider
     */
    public function testCanNavigateFromDirectory(string $current, string $prev, string $next): void
    {
        $url = $this->urlGenerator->generate('app_explore', ['path' => $current]);
        $this->client->request('GET', $url);
        $crawler = $this->client->getCrawler();
        $prevUrl = $crawler->filter('[data-e2e="prev-link"]')->link();
        $nextUrl = $crawler->filter('[data-e2e="next-link"]')->link();

        self::assertStringEndsWith(rawurlencode($prev), $prevUrl->getUri());
        self::assertStringEndsWith(rawurlencode($next), $nextUrl->getUri());
    }

    public static function directoryProvider(): array
    {
        return [
            'first directory' => ['Series 1', 'Series 1', 'Series 2'],
            'last directory' => ['empty-directory', 'archive copy #12.zip', 'empty.zip'],
            'directory before archive' => ['Series 2', 'Series 1', 'archive.zip'],
        ];
    }

    /**
     * @dataProvider archiveProvider
     */
    public function testCanNavigateFromArchive(string $current, string $prev, string $next): void
    {
        $url = $this->urlGenerator->generate('app_archive_list', ['path' => $current]);
        $this->client->request('GET', $url);
        $crawler = $this->client->getCrawler();
        $prevUrl = $crawler->filter('[data-e2e="prev-link"]')->link();
        $nextUrl = $crawler->filter('[data-e2e="next-link"]')->link();

        self::assertStringEndsWith(rawurlencode($prev), $prevUrl->getUri());
        self::assertStringEndsWith(rawurlencode($next), $nextUrl->getUri());
    }

    public static function archiveProvider(): array
    {
        return [
            'first archive' => ['archive.zip', 'Series 2', 'archive copy #12.zip'],
            'archive before directory' => ['empty.zip', 'empty-directory', 'nested-archive.zip'],
            'archive after directory' => ['archive copy #12.zip', 'archive.zip', 'empty-directory'],
            'last archive' => ['nested-archive.zip', 'empty.zip', 'nested-archive.zip'],
        ];
    }
}
