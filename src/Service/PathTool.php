<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PathTool
{
    /**
     * @var Request|null
     */
    private $request;
    /**
     * @var string
     */
    private $mangaRoot;

    public function __construct(RequestStack $request, string $mangaRoot)
    {
        $this->request = $request->getCurrentRequest();
        $this->mangaRoot = $mangaRoot;
    }

    public function getUri(): string
    {
        if (null === $this->request) {
            return '/';
        }

        return trim(rawurldecode($this->request->getPathInfo()), '/');
    }

    public function getPrefix(): string
    {
        return '/' === $this->getUri() ? '' : rawurldecode($this->getUri());
    }

    public function getTarget(): string
    {
        return $this->mangaRoot.'/'.$this->getPrefix();
    }

    public function getTargetParent(): string
    {
        return dirname($this->getTarget());
    }
}
