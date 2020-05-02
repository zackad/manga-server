<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class PathTool
{
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }

    public function getUri(): string
    {
        return trim(urldecode($this->request->getRequestUri()), '/');
    }

    public function getPrefix(): string
    {
        return '/' === $this->getUri() ? '' : urldecode($this->getUri());
    }

    public function getTarget(): string
    {
        return $_ENV['MANGA_ROOT_DIRECTORY'].$this->getPrefix();
    }
}
