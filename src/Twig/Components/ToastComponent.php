<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('toast')]
final class ToastComponent
{
    public string $type = 'success';
    public string $message;
    public bool $show = false;
}
