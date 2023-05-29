<?php

namespace App\Twig\Components;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('trick_list')]
final class TrickListComponent
{
    use DefaultActionTrait;

    public const NUMBER_TRICK = 3;

    #[LiveProp(writable: true)]
    public int $page = 0;

    public function __construct(private TrickRepository $trickRepository)
    {
    }

    #[LiveAction]
    public function increment(): void
    {
        ++$this->page;
    }

    /**
     * getTricks.
     *
     * @return array<Trick>
     */
    public function getTricks(): array
    {
        return $this->trickRepository->findBy(
            ['published' => true, 'valided' => true],
            ['createdAt' => 'DESC'],
            self::NUMBER_TRICK * ($this->page + 1),
            0
        );
    }

    public function showButton(): bool
    {
        $count = $this->trickRepository->count(['published' => true, 'valided' => true]);

        return self::NUMBER_TRICK * ($this->page + 1) < $count;
    }
}
