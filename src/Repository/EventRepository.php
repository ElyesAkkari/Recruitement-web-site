<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

     /**
      * @return Event[] Returns an array of Event objects
     */

    public function findThree()
    {
        return $this->createQueryBuilder('e')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function RecherhceEventParId($id){
        return $this->createQueryBuilder('event')
            ->andWhere('event.date LIKE :id OR event.nom LIKE :id OR event.type LIKE :id')
            ->setParameter('id', '%'.$id.'%')
            ->getQuery()
            ->getResult();
    }
    public function TriEventParNom(){
        return $this->createQueryBuilder('event')
            ->orderBy('event.nom','ASC')
            ->getQuery()
            ->getResult();
    }
    public function TriEventParPrix(){
        return $this->createQueryBuilder('event')
            ->orderBy('event.prix', 'ASC')
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



    public function stat1()
    {
        $query= $this->getEntityManager()
            ->createQuery('select o.type,count(o.id) as nbdep from App\Entity\Event o group by o.type');
        return $query->getResult();
    }
}
