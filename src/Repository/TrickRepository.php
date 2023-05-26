<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trick>
 *
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function save(Trick $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trick $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * search.
     *
     * @return array<mixed>
     */
    public function search(string $query = ''): array
    {
        if (empty($query)) {
            return [];
        }

        /* @phpstan-ignore-next-line */
        return $this->createQueryBuilder('t')
            ->andWhere('t.title LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('t.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * findByCategory.
     *
     * @return array<mixed>
     */
    public function findByCategory(Category $category, int $exclude, int $limit = 3): array
    {
        /* @phpstan-ignore-next-line */
        return $this->createQueryBuilder('t')
            ->andWhere('t.category = :cat')
            ->andWhere('t.id != :exclude')
            ->andWhere('t.published = true')
            ->andWhere('t.valided = true')
            ->select('t.id, t.title, t.slug')
            ->setParameter('cat', $category)
            ->setParameter('exclude', $exclude)
            ->orderBy('t.title', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
