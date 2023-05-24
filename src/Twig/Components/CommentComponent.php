<?php

namespace App\Twig\Components;

use App\Entity\Comment;
use App\Entity\CommentSignaled;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\Comment\CommentType;
use App\Repository\CommentRepository;
use App\Repository\CommentSignaledRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('comment')]
final class CommentComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public Trick $trick;

    #[LiveProp(writable: true)]
    public ?string $reason = null;

    /** @var array<mixed> */
    public array $changes = [];

    public function __construct(
        private CommentRepository $commentRepository,
        private CommentSignaledRepository $commentSignaledRepository
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CommentType::class, new Comment());
    }

    #[LiveAction]
    public function addComment(): void
    {
        $this->submitForm();

        /** @var Comment $comment */
        $comment = $this->getFormInstance()->getData();

        /** @var User $user */
        $user = $this->getUser();

        $comment->setAuthor($user);
        $comment->setTrick($this->trick);

        $this->commentRepository->save($comment, true);

        $this->changes['label'] = 'success';
        $this->changes['message'] = 'Votre commentaire a bien été ajouté';
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

        $this->commentRepository->remove($commentRemove, true);

        $this->changes['label'] = 'success';
        $this->changes['message'] = 'Votre commentaire a bien été supprimé';
    }

    #[LiveAction]
    public function signaledComment(#[LiveArg] int $id): void
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->find($id);
        if (!$comment instanceof Comment) {
            $this->changes['label'] = 'danger';
            $this->changes['message'] = 'Un problème est survenue';

            return;
        }

        $isExists = $this->commentSignaledRepository->findOneBy(['comment' => $comment]);
        if ($isExists instanceof CommentSignaled) {
            $this->changes['label'] = 'danger';
            $this->changes['message'] = 'Ce commentaire a déjà été signalé';

            return;
        }

        $commentSignaled = new CommentSignaled();
        $commentSignaled
            ->setReason($this->reason) /* @phpstan-ignore-line */
            ->setComment($comment)
        ;

        $this->commentSignaledRepository->save($commentSignaled, true);

        $this->changes['label'] = 'success';
        $this->changes['message'] = 'Votre signalement a bien été enregistré';
    }

    /**
     * getComments.
     *
     * @return array<mixed>
     */
    public function getComments(): array
    {
        return $this->commentRepository->findBy(['trick' => $this->trick], ['createdAt' => 'DESC']);
    }
}
