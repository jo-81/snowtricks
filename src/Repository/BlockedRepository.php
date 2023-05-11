<?php

namespace App\Repository;

use App\Entity\Blocked;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blocked>
 *
 * @method Blocked|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blocked|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blocked[]    findAll()
 * @method Blocked[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blocked::class);
    }

    public function save(Blocked $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Blocked $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
