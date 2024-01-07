<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NextChapterResolver
{
    public function __construct(
        private readonly string $mangaRoot,
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolve(): string
    {
        $request = $this->requestStack->getMainRequest();
        if (!$request instanceof Request) {
            return $this->urlGenerator->generate('app_explore');
        }
        $path = (string) $request->query->get('path', '');
        $decodedPath = trim(rawurldecode($path), '/');
        $parent = dirname(sprintf('%s/%s', $this->mangaRoot, $decodedPath));

        $entries = iterator_to_array($this->getEntry($parent));

        $currentEntry = '/'.$decodedPath;
        if ('/' === $currentEntry) {
            return $this->urlGenerator->generate('app_explore');
        }

        $nextEntry = $this->getNext($entries, $currentEntry);

        if ('' === $nextEntry) {
            $parentUrl = str_replace($this->mangaRoot, '', $parent);

            if (!$parentUrl) {
                return $this->urlGenerator->generate('app_explore');
            }

            return $this->urlGenerator->generate('app_explore', ['path' => $parentUrl]);
        }

        return $this->urlGenerator->generate('app_explore', ['path' => $nextEntry]);
    }

    private function getEntry(string $directory): \Generator
    {
        $finder = new Finder();
        $finder->in($directory)->directories()->depth('== 0')->sortByName(true);

        /** @var SplFileInfo $entry */
        foreach ($finder as $entry) {
            // Fix windows path separator
            $fixedPath = str_replace('\\', '/', $entry->getPathname());
            yield str_replace($this->mangaRoot, '', $fixedPath);
        }
    }

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
}
