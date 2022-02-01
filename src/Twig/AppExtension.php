<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /** @var Request|null */
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getMainRequest();
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('is_image', [$this, 'isImage']),
            new TwigFilter('get_title', [$this, 'getTitleFromUri']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_image', [$this, 'isImage']),
        ];
    }

    public function isImage(string $value): bool
    {
        return (bool) preg_match('/\.(jpg|jpeg|png|webp)$/i', $value);
    }

    public function getTitleFromUri(): ?string
    {
        if (null === $this->request) {
            return null;
        }

        $decodedUri = urldecode($this->request->getRequestUri());
        $array = preg_split('/\//', $decodedUri, -1, PREG_SPLIT_NO_EMPTY);

        return array_pop($array);
    }
}
