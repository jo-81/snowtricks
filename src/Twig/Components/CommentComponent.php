<?php

namespace App\Twig\Components;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('comment')]
final class CommentComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Trick $trick;

    /** @var array<mixed> */
    #[LiveProp]
    public array $changes = [];

    public function __construct(private CommentRepository $commentRepository)
    {
    }

    #[LiveAction]
    public function removeComment(#[LiveArg] int $index): void
    {
        $comments = $this->getComments();

        /** @var Comment */
        $commentRemove = $comments[$index];

        if (!$this->isGranted('COMMENT_DELETE', $commentRemove)) {
            $this->changes['label'] = 'danger';
            $this->changes['message'] = 'Vous ne pouvez pas supprimer ce commentaire';

            return;
        }

        $this->changes['label'] = 'success';
        $this->changes['message'] = 'Votre commentaire a bien été supprimé';

        $this->commentRepository->remove($commentRemove, true);
    }

    /**
     * getComments.
     *
     * @return array<mixed>
     */
    public function getComments(): array
    {
        return $this->commentRepository->findBy(['trick' => $this->trick]);
    }
}
