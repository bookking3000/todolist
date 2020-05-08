<?php

namespace App\Repository;

use App\Entity\Todo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Todo::class);
    }

   /**
    * @return Todo[] Returns an array of owned Todo objects
    */
    public function findByOwner($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.Owner = :val')
            ->setParameter('val', $value)
            ->orderBy('t.DueDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
