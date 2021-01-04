<?php

namespace App\Service;

use Symfony\Component\Finder\Finder;

class NextChapterResolver
{
    private PathTool $pathTool;
    private string $mangaRoot;

    public function __construct(PathTool $pathTool, string $mangaRoot)
    {
        $this->pathTool = $pathTool;
        $this->mangaRoot = $mangaRoot;
    }

    public function resolve(): string
    {
        $parent = $this->pathTool->getTargetParent();

        $entries = iterator_to_array($this->getEntry($parent));

        $uri = '/'.$this->pathTool->getUri();
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
            return current($array);
        }

        return prev($array);
    }
}
