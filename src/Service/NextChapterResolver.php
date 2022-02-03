<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class NextChapterResolver
{
    /**
     * @var string
     */
    private $mangaRoot;
    /**
     * @var Request|null
     */
    private $request;

    public function __construct(string $mangaRoot, RequestStack $requestStack)
    {
        $this->mangaRoot = $mangaRoot;
        $this->request = $requestStack->getMainRequest();
    }

    public function resolve(): string
    {
        if (!$this->request instanceof Request) {
            return '/';
        }
        $path = $this->request->attributes->get('path', '');
        $parent = dirname(sprintf('%s/%s', $this->mangaRoot, $path));

        $entries = iterator_to_array($this->getEntry($parent));

        $uri = '/'.$path;
        if ('/' === $uri) {
            return $uri;
        }

        $nexpage = $this->getNext($entries, $uri);

        if (!$nexpage) {
            $parentUrl = rawurlencode(str_replace($this->mangaRoot, '', $parent));

            if (!$parentUrl) {
                return '/';
            }

            return $parentUrl;
        }

        return rawurlencode($nexpage);
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
