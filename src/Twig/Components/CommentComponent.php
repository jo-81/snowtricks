<?php

namespace App\Twig\Components;

use App\Entity\Trick;
use App\Repository\CommentRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('comment')]
final class CommentComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?Trick $trick = null;

    public const NUMBER_COMMENT = 3;

    #[LiveProp(writable: true)]
    public int $page = 0;

    public function __construct(private CommentRepository $commentRepository)
    {
    }

    public function mount(Trick $trick): void
    {
        $this->trick = $trick;
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

    /**
     * getComments.
     *
     * @return array<mixed>
     */
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
        return self::NUMBER_COMMENT * ($this->page + 1) < $this->getCount();
    }
}
