<?php

namespace App\Twig\Components;

use App\Entity\Comment;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('card_comment')]
final class CardCommentComponent
{
    public Comment $comment;
}
