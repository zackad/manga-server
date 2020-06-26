<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class PathTool
{
    private $request;
    private $mangaRoot;

    public function __construct(RequestStack $request, string $mangaRoot)
    {
        $this->request = $request->getCurrentRequest();
        $this->mangaRoot = $mangaRoot;
    }

    public function getUri(): string
    {
        return trim(rawurldecode($this->request->getRequestUri()), '/');
    }

    public function getPrefix(): string
    {
        return '/' === $this->getUri() ? '' : rawurldecode($this->getUri());
    }

    public function getTarget(): string
    {
        return $this->mangaRoot.'/'.$this->getPrefix();
    }
}
