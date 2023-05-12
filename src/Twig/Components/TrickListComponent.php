<?php

namespace App\Twig\Components;

use App\Repository\TrickRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent('trick_list')]
final class TrickListComponent
{
    use DefaultActionTrait;

    public const NUMBER_TRICK = 3;

    #[LiveProp(writable: true)]
    public int $page = 0;

    public function __construct(private TrickRepository $trickRepository)
    {}

    #[LiveAction]
    public function increment(): void
    {
        $this->page++;
    }

    public function getTricks(): array
    {
        return $this->trickRepository->findBy([], ['createdAt' => 'DESC'], self::NUMBER_TRICK * ($this->page + 1) , 0);
    }

    public function showButton(): bool
    {
        $count = $this->trickRepository->count([]);
        return self::NUMBER_TRICK * ($this->page + 1) < $count;
    }
}