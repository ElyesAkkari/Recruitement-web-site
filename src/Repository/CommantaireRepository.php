<?php

namespace App\Repository;

use App\Entity\Commantaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commantaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commantaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commantaire[]    findAll()
 * @method Commantaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommantaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commantaire::class);
    }

    // /**
    //  * @return Commantaire[] Returns an array of Commantaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commantaire
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
