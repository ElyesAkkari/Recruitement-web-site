<?php

namespace App\Repository;

use App\Entity\Offreserch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offreserch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offreserch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offreserch[]    findAll()
 * @method Offreserch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreserchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offreserch::class);
    }

    // /**
    //  * @return Offreserch[] Returns an array of Offreserch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offreserch
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
