<?php

namespace App\Twig\Components;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\Comment\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent('comment')]
final class CommentComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ValidatableComponentTrait;

    public const NUMBER_COMMENT = 3;

    #[LiveProp]
    public Trick $trick;

    #[LiveProp(writable: true)]
    public ?string $content = null;

    public int $count;

    /** @var array<mixed> */
    public array $changes = [];

    #[LiveProp(writable: true)]
    public int $page = 0;

    public function __construct(private CommentRepository $commentRepository)
    {
    }

    #[LiveAction]
    public function increment(): void
    {
        ++$this->page;
    }

    public function getCount(): int
    {
        return $this->commentRepository->count(['trick' => $this->trick]);
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CommentType::class, new Comment());
    }

    #[LiveAction]
    public function addComment(): Response
    {
        $this->submitForm();

        /** @var Comment $comment */
        $comment = $this->getFormInstance()->getData();

        /** @var User $user */
        $user = $this->getUser();

        $comment->setAuthor($user);
        $comment->setTrick($this->trick);
        $this->commentRepository->save($comment, true);

        $this->addFlash('success', 'Votre commentaire a bien été ajouté');

        return $this->redirectToRoute('trick.show', ['slug' => $this->trick->getSlug()]);
    }

    /**
     * getComments.
     *
     * @return array<mixed>
     */
    #[LiveListener('commentEvent')]
    public function getComments(): array
    {
        return $this->commentRepository->findBy(
            ['trick' => $this->trick],
            ['createdAt' => 'DESC'],
            self::NUMBER_COMMENT * ($this->page + 1),
            0
        );
    }

    public function showButton(): bool
    {
        $count = $this->commentRepository->count(['trick' => $this->trick]);

        return self::NUMBER_COMMENT * ($this->page + 1) < $count;
    }
}
