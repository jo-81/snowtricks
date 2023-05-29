<?php

namespace App\Twig\Components;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('trick_list_and_search')]
final class TrickListAndSearchComponent
{
    use DefaultActionTrait;

    public const NUMBER_TRICK = 5;

    #[LiveProp(writable: true)]
    public string $query = '';

    #[LiveProp(writable: true)]
    public int $page = 0;

    #[LiveProp]
    public string $categoryName = 'all';

    public function __construct(private TrickRepository $trickRepository, private CategoryRepository $categoryRepository)
    {
    }

    #[LiveAction]
    public function increment(): void
    {
        ++$this->page;
    }

    #[LiveAction]
    public function setCategoryName(#[LiveArg] string $categoryName): void
    {
        if (!empty($this->query)) {
            $this->categoryName = 'all';
        } else {
            $this->categoryName = $categoryName;
        }
    }

    /**
     * getCategories.
     *
     * @return array<Category>
     */
    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * getTricks.
     *
     * @return array<mixed>
     */
    public function getTricks(): array
    {
        if (!empty($this->query)) {
            return $this->trickRepository->search($this->query);
        }

        return $this->trickRepository->findBy($this->getCriteria(), ['title' => 'ASC'], self::NUMBER_TRICK * ($this->page + 1), 0);
    }

    public function showButton(): bool
    {
        $count = $this->trickRepository->count($this->getCriteria());

        return self::NUMBER_TRICK * ($this->page + 1) < $count;
    }

    /**
     * getCriteria.
     *
     * @return array<mixed>
     */
    private function getCriteria(): array
    {
        if ('all' == $this->categoryName) {
            return ['published' => true, 'valided' => true];
        }

        return [
            'published' => true,
            'valided' => true,
            'category' => $this->categoryRepository->findOneBy(['name' => $this->categoryName]),
        ];
    }
}
