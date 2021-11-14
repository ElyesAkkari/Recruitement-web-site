<?php

namespace App\Repository;

use App\Entity\Sponsor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sponsor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sponsor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sponsor[]    findAll()
 * @method Sponsor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SponsorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sponsor::class);
    }

    // /**
    //  * @return Sponsor[] Returns an array of Sponsor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sponsor
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function RecherhceSponsorParId($id){
        return $this->createQueryBuilder('sponsor')
            ->andWhere('sponsor.id LIKE :id OR sponsor.nom LIKE :id OR sponsor.type LIKE :id')
            ->setParameter('id', '%'.$id.'%')
            ->getQuery()
            ->getResult();
    }
    public function TriSponsorParNom(){
        return $this->createQueryBuilder('sponsor')
            ->orderBy('sponsor.nom','ASC')
            ->getQuery()
            ->getResult();
    }
    public function TriSponsorParType(){
        return $this->createQueryBuilder('sponsor')
            ->orderBy('sponsor.type', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function TriSponsorParEvent(){
        return $this->createQueryBuilder('sponsor')
            ->orderBy('sponsor.event', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
