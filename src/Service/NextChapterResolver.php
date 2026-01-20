<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class NextChapterResolver
{
    public function __construct(
        #[Autowire('%env(resolve:APP_MEDIA_DIRECTORY)%')]
        private readonly string $mangaRoot,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TagAwareCacheInterface $cache,
    ) {
    }

    public function nextUrl(string $route, string $filename): string
    {
        $decodedPath = trim(rawurldecode($filename), '/');
        $parent = dirname(sprintf('%s/%s', $this->mangaRoot, $decodedPath));

        $entries = $this->getEntry($parent);
        $currentEntry = $decodedPath;
        $nextEntry = $this->getNext($entries, $currentEntry);
        if (empty($nextEntry)) {
            return $this->urlGenerator->generate($route, ['path' => $currentEntry]);
        }

        $pattern = "/\.(?:cbz|epub|zip)$/i";
        if (preg_match($pattern, $nextEntry)) {
            return $this->urlGenerator->generate('app_archive_list', ['path' => $nextEntry]);
        }

        return $this->urlGenerator->generate('app_explore', ['path' => $nextEntry]);
    }

    public function prevUrl(string $route, string $filename): string
    {
        $decodedPath = trim(rawurldecode($filename), '/');
        $parent = dirname(sprintf('%s/%s', $this->mangaRoot, $decodedPath));

        $entries = $this->getEntry($parent);
        $currentEntry = $decodedPath;
        $prevEntry = $this->getPrev($entries, $currentEntry);
        if (empty($prevEntry)) {
            return $this->urlGenerator->generate($route, ['path' => $currentEntry]);
        }

        $pattern = "/\.(?:cbz|epub|zip)$/i";
        if (preg_match($pattern, $prevEntry)) {
            return $this->urlGenerator->generate('app_archive_list', ['path' => $prevEntry]);
        }

        return $this->urlGenerator->generate('app_explore', ['path' => $prevEntry]);
    }

    /**
     * @return string[]
     */
    private function getEntry(string $directory): array
    {
        return $this->cache->get(sprintf('entry-%s', md5($directory)), function (ItemInterface $item) use ($directory) {
            $item->expiresAt(new \DateTimeImmutable('+1 week'));
            $item->tag(['entry']);

            $results = [];
            $finder = new Finder();
            $finder->in($directory)
                ->depth('== 0')
                ->filter(fn (\SplFileInfo $fileInfo) => $fileInfo->isDir() || in_array($fileInfo->getExtension(), ['cbz', 'epub', 'zip']))
                ->sortByName(true);

            /** @var SplFileInfo $entry */
            foreach ($finder as $entry) {
                // Fix windows path separator
                $fixedPath = str_replace('\\', '/', $entry->getPathname());
                $results[] = trim(str_replace($this->mangaRoot, '', $fixedPath), '/');
            }

            return $results;
        });
    }

    /**
     * @param string[] $array
     */
    private function getNext(array $array, string $haystack): string
    {
        reset($array);

        while (!in_array(current($array), [$haystack, null])) {
            next($array);
        }

        if (false !== next($array)) {
            return (string) current($array);
        }

        return (string) prev($array);
    }

    /**
     * @param string[] $array
     */
    private function getPrev(array $array, string $haystack): string
    {
        reset($array);

        while (!in_array(current($array), [$haystack, null])) {
            next($array);
        }

        if (false !== prev($array)) {
            return (string) current($array);
        }

        return (string) next($array);
    }
}
