<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\PlurialExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PlurialExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [PlurialExtensionRuntime::class, 'doSomething']), /* @phpstan-ignore-line */
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('plurial', [PlurialExtensionRuntime::class, 'doPlurial']),
        ];
    }
}
