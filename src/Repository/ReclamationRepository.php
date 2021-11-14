<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    // /**
    //  * @return Reclamation[] Returns an array of Reclamation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reclamation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function RechercheReclamationParId($id){
        return $this->createQueryBuilder('reclamation')
            ->andWhere('reclamation.contenu LIKE :id OR reclamation.id LIKE :id')
            ->setParameter('id', '%'.$id.'%')
            ->getQuery()
            ->getResult();
    }
    public function TriReclamationParContenu(){
        return $this->createQueryBuilder('reclamation')
            ->orderBy('reclamation.Contenu','ASC')
            ->getQuery()
            ->getResult();
    }
    public function TriReclamationParDate(){
        return $this->createQueryBuilder('reclamation')
            ->orderBy('reclamation.event', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function TriEventParDate(){
        return $this->createQueryBuilder('event')
            ->orderBy('event.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function FiltreEventPrix(int $prix){
        return $this->createQueryBuilder('event')
            ->Where('event.prix < :prix')
            ->setParameter('prix','%'.$prix.'%')
            ->getQuery()
            ->getResult();
    }
}
