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
            return rawurlencode(str_replace($this->mangaRoot, '', $parent));
        }

        return rawurlencode($nexpage);
    }

    private function getEntry(string $directory): \Generator
    {
        $finder = new Finder();
        $finder->in($directory)->directories()->depth('== 0');

        foreach ($finder as $entry) {
            yield str_replace($this->mangaRoot, '', $entry->getPathname());
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
