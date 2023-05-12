<?php

namespace App\Twig\Components;

use App\Entity\Trick;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('card_trick')]
final class CardTrickComponent
{
    public Trick $trick;
}
