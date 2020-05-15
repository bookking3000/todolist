<?php

namespace App\Repository;

use App\Entity\TodoCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TodoCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoCategory[]    findAll()
 * @method TodoCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoCategory::class);
    }

    public function findByOwner($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :val')
            ->setParameter('val', $value)
            ->orderBy('t.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
