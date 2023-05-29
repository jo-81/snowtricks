<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class InstanceOfExtensionRuntime implements RuntimeExtensionInterface
{
    public function isInstanceOf(Object $object, string $class): bool
    {
        return $object instanceof $class;
    }
}
