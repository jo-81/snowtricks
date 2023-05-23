<?php

namespace App\Twig\Components;

use App\Entity\Comment;
use App\Entity\CommentSignaled;
use App\Form\Comment\CommentSignaledType;
use App\Repository\CommentSignaledRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('card_comment')]
final class CardCommentComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public function __construct(private CommentSignaledRepository $commentSignaledRepository)
    {
    }

    #[LiveProp]
    public Comment $comment;

    #[LiveProp(fieldName: 'data')]
    public ?CommentSignaled $commentSignaled = null;

    public bool $isPersist = false;

    public function getValided(): bool
    {
        $commentSignaled = $this->commentSignaledRepository->findOneBy(['comment' => $this->comment->getId()]);
        if (is_null($commentSignaled)) {
            return true;
        }

        return !$commentSignaled->isValided();
    }

    public function isSignaled(): bool
    {
        $commentSignaled = $this->commentSignaledRepository->findOneBy(['comment' => $this->comment]);
        if ($commentSignaled instanceof CommentSignaled) {
            return true;
        }

        return false;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CommentSignaledType::class, $this->commentSignaled);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        $this->submitForm();

        /** @var CommentSignaled $commentSignaled */
        $commentSignaled = $this->getFormInstance()->getData();
        $commentSignaled->setComment($this->comment);

        $entityManager->persist($commentSignaled);
        $entityManager->flush();

        $this->isPersist = true;
    }
}
