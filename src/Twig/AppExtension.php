<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /** @var Request|null */
    private $request;
    /**
     * @var Environment
     */
    private $twig;
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    public function __construct(RequestStack $requestStack, Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->request = $requestStack->getMainRequest();
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('is_image', [$this, 'isImage']),
            new TwigFilter('filter_image', [$this, 'filterImage']),
            new TwigFilter('filter_cover', [$this, 'filterCover']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_title', [$this, 'getTitleFromUri']),
            new TwigFunction('is_image', [$this, 'isImage']),
            new TwigFunction('render_breadcrumbs', [$this, 'renderBreadcrumbs'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function filterImage(\Traversable $items): iterable
    {
        $items = iterator_to_array($items);

        return array_filter($items, function ($item) {
            return $this->isImage($item['uri']);
        });
    }

    public function filterCover(\Traversable $items, bool $withCover = true): iterable
    {
        $items = iterator_to_array($items);

        return array_filter($items, function ($item) use ($withCover) {
            return $withCover ? $item['cover'] : !$item['cover'];
        });
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

        $decodedUri = urldecode((string) $this->request->query->get('path', ''));
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
        if (null === $this->request) {
            return null;
        }

        $target = $this->request->query->get('path') ?? $this->request->attributes->get('path');
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
