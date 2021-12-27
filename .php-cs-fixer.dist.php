<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__.'/src', __DIR__.'/tests'])
;

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@Symfony' => true,
    'declare_strict_types' => true,
])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
;
