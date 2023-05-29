<?php

namespace App\Twig\Components;

use App\Entity\Comment;
use App\Form\Comment\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('card_comment')]
final class CardCommentComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentToolsTrait;
    use ComponentWithFormTrait;

    #[LiveProp(updateFromParent: true, fieldName: 'commentaire', writable: ['content'])]
    public Comment $comment;

    #[LiveProp(writable: true)]
    public ?string $reason = null;

    public function __construct(private CommentRepository $commentRepository)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CommentType::class, $this->comment);
    }

    #[LiveAction]
    public function removeComment(#[LiveArg] int $id): void
    {
        /** @var Comment */
        $comment = $this->commentRepository->find($id);
        if (!$this->isGranted('COMMENT_DELETE', $comment)) {
            return;
        }

        $this->commentRepository->remove($comment, true);
        $this->emitUp('commentEvent');
    }

    #[LiveAction]
    public function editComment(): void
    {
        $this->submitForm();

        /** @var Comment $comment */
        $comment = $this->getFormInstance()->getData();
        $this->commentRepository->save($comment, true);
        $this->emitUp('commentEvent');
    }
}
