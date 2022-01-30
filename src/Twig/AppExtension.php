<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('is_image', [$this, 'isImage']),
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
}
