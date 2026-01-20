<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly Environment $twig,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('is_image', $this->isImage(...)),
            new TwigFilter('filter_image', $this->filterImage(...)),
            new TwigFilter('filter_cover', $this->filterCover(...)),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_title', $this->getTitleFromUri(...)),
            new TwigFunction('is_image', $this->isImage(...)),
            new TwigFunction('render_breadcrumbs', $this->renderBreadcrumbs(...), ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function filterImage(\Traversable $items): iterable
    {
        $items = iterator_to_array($items);

        return array_filter($items, fn ($item) => $this->isImage($item['uri']));
    }

    public function filterCover(\Traversable $items, bool $withCover = true): iterable
    {
        $items = iterator_to_array($items);

        return array_filter($items, fn ($item) => $withCover ? $item['cover'] : !$item['cover']);
    }

    public function isImage(string $value): bool
    {
        return (bool) preg_match('/\.(jpg|jpeg|png|webp)$/i', $value);
    }

    public function getTitleFromUri(?string $path = null): ?string
    {
        $decodedUri = urldecode((string) $path);
        if ('' === $decodedUri) {
            return null;
        }
        /** @var string[] $array */
        $array = preg_split('/\//', $decodedUri, -1, PREG_SPLIT_NO_EMPTY);
        $dirtyString = (string) array_pop($array);

        return explode('?', $dirtyString)[0];
    }

    public function renderBreadcrumbs(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return null;
        }

        $target = $request->query->get('path') ?? $request->attributes->get('path');
        $decodedTarget = rawurldecode((string) $target);
        $breadcumbs = preg_split('/\//', $decodedTarget, -1, PREG_SPLIT_NO_EMPTY);

        $target = '';
        $items = [];
        foreach ($breadcumbs as $crumb) {
            $target .= '/'.$crumb;
            $items[] = [
                'label' => $crumb,
                'uri' => $this->urlGenerator->generate('app_explore', ['path' => $target]),
            ];
        }

        return $this->twig->render('partial/_breadcrumbs.html.twig', [
            'breadcrumbs' => $items,
        ]);
    }
}
