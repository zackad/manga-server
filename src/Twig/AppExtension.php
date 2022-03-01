<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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

    public function __construct(RequestStack $requestStack, Environment $twig)
    {
        $this->request = $requestStack->getMainRequest();
        $this->twig = $twig;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('is_image', [$this, 'isImage']),
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
        $dirtyString = array_pop($array);

        return explode('?', $dirtyString)[0];
    }

    public function renderBreadcrumbs(): ?string
    {
        if (null === $this->request) {
            return null;
        }

        $target = $this->request->attributes->get('path', '');
        $breadcumbs = preg_split('/\//', $target, -1, PREG_SPLIT_NO_EMPTY);

        $target = '';
        $items = [];
        foreach ($breadcumbs as $crumb) {
            $target .= '/'.$crumb;
            $items[] = [
                'label' => $crumb,
                'uri' => $target,
            ];
        }

        return $this->twig->render('partial/_breadcrumbs.html.twig', [
            'breadcrumbs' => $items,
        ]);
    }
}
