<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class PlurialExtensionRuntime implements RuntimeExtensionInterface
{
    public function doPlurial(int $number, string $single, string $plurial): string
    {
        return $number >= 2 ? $plurial : $single;
    }
}
