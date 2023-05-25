<?php

namespace App\Twig\Components;

use App\Entity\Comment;
use App\Entity\CommentSignaled;
use App\Form\Comment\CommentType;
use App\Repository\CommentRepository;
use App\Repository\CommentSignaledRepository;
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

    public function __construct(private CommentRepository $commentRepository, private CommentSignaledRepository $commentSignaledRepository)
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

    #[LiveAction]
    public function signaledComment(#[LiveArg] int $id): void
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->find($id);
        if (!$comment instanceof Comment) {
            return;
        }

        $isExists = $this->commentSignaledRepository->findOneBy(['comment' => $comment]);
        if ($isExists instanceof CommentSignaled) {
            return;
        }

        $commentSignaled = new CommentSignaled();
        $commentSignaled
            ->setReason($this->reason) /* @phpstan-ignore-line */
            ->setComment($comment)
        ;

        $this->commentSignaledRepository->save($commentSignaled, true);
        $this->emitUp('commentEvent');
    }
}
