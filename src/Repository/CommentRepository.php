<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function save(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function findCommentByTrickValided(Trick $trick, int $number)
    // {
    //     $query = $this->createQueryBuilder('c')
    //         ->andWhere('c.trick = :trick')
    //         ->setParameter('trick', $trick)
    //         ->orderBy('c.id', 'DESC')
    //         ->join(join: 'App\Entity\CommentSignaled', alias: 'cs', conditionType: Expr\Join::WITH, condition: 'c.id <> cs.comment')
    //         ->setFirstResult(0)
    //         ->setMaxResults($number)
    //         ->getQuery()
    //     ;

    //     $paginator = new Paginator($query, true);

    //     return $paginator;
    // }
}
