<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Service\NextChapterResolver;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class NavigationButton
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $path;

    #[LiveProp]
    public string $routeName;

    public function __construct(
        private readonly NextChapterResolver $nextChapterResolver,
    ) {
    }

    public function prevUrl(): string
    {
        return $this->nextChapterResolver->prevUrl($this->routeName, $this->getDecodedPath());
    }

    public function nextUrl(): string
    {
        return $this->nextChapterResolver->nextUrl($this->routeName, $this->getDecodedPath());
    }

    private function getDecodedPath(): string
    {
        return rawurldecode($this->path);
    }
}
