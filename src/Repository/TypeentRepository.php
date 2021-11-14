<?php

namespace App\Repository;

use App\Entity\Typeent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Typeent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typeent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typeent[]    findAll()
 * @method Typeent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeent::class);
    }

    // /**
    //  * @return Typeent[] Returns an array of Typeent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Typeent
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
