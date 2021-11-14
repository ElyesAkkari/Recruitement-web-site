<?php

namespace App\Repository;

use App\Entity\Entretient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Entretient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entretient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entretient[]    findAll()
 * @method Entretient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntretientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entretient::class);
    }

    // /**
    //  * @return Entretient[] Returns an array of Entretient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Entretient
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param $recruteur
     * @return int|mixed|string
     */
    function Searchrecruteur($recruteur)
    {
        return $this->createQueryBuilder('e')
            ->where('.recruteur LIKE :recruteur')
            ->setParameter('recruteur', '%' . $recruteur . '%')
            ->getQuery()->getResult();
    }

    /**
     * @param $data
     * @return int|mixed|string
     */
    public function search($data)
    {
        $query = $this->createQueryBuilder('u');
        if($data){
            $query->andWhere('
                u.id LIKE :data OR
                u.datedeb LIKE :data OR
                u.recruteur LIKE :data OR
                u.resultat LIKE :data OR
                u.temps LIKE :data OR
                u.mail LIKE :data OR
                u.etat LIKE :data 
            ')
                ->setParameter('data','%'.$data.'%');
        }
        return $query
            ->getQuery()
            ->getResult();
    }

    //Count entretien date month
    public function moisEntretien($mois){
        $result = $this->getEntityManager()->createQuery('SELECT p FROM App:Entretient p WHERE 
        MONTH(p.datedeb) = :m')
            ->setParameter('m',$mois)
            ->getResult();
        return  $result;
    }
}
