<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Offreserch;

/**
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    // /**
    //  * @return Offre[] Returns an array of Offre objects
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
    public function findById($value){
        return $this->createQueryBuilder('o')
            ->where('o.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findThreee()
    {
        return $this->createQueryBuilder('o')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
        ;
    }

    public function rechercheroffre($noms){
        return $this->createQueryBuilder('m')
            ->where('m.noms LIKE :noms')

            ->setParameter('noms','%'.$noms.'%')
            ->getQuery()
            ->getResult();

    }



    public function stat1()
    {
        $query= $this->getEntityManager()
            ->createQuery('select o.departement,count(o.id) as nbdep from App\Entity\Offre o group by o.departement');
        return $query->getResult();
    }



    public function stat ()
    { return $this->createQueryBuilder('o')
        ->select('o.departement')
        ->select('count(o.id)')
        ->groupBy('o.departement')
        ->getQuery()->getResult();

    }



    public function QueryDepartement(Offreserch $os): array
    {

        return $query=$this->createQueryBuilder('m')


            ->andWhere('m.departement >= :sdep')
            ->setParameter('sdep', $os->getSdep())
            ->getQuery()
            ->getResult();

    }
}
