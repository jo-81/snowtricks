<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class InstanceOfExtensionRuntime implements RuntimeExtensionInterface
{
    public function isInstanceOf($object, string $class)
    {
        return $object instanceof $class;
    }
}
