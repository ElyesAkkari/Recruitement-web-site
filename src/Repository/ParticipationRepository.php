<?php

namespace App\Repository;

use App\Entity\Participation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participation[]    findAll()
 * @method Participation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }

    // /**
    //  * @return Participation[] Returns an array of Participation objects
    //  */

    public function findExist($value,$offre) :?Participation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :val')
            ->andWhere('p.offre = :offre')
            ->setParameter('val', $value)
            ->setParameter('offre', $offre)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findNullQuiz(){
        return $this->createQueryBuilder('p')
            ->where('p.note = :val')
            ->setParameter('val',-1)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Participation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     *@param $data
     *return int|mixed|string
     */
    public function search ($data)
    {
        $query = $this->createQueryBuilder('p');
        if ($data) {
            $query->andWhere('
                        p.id LIKE :data OR
                        p.note LIKE :data OR
                        p.added LIKE :data 
                        ')
                ->setParameter('data', '%' . $data . '%');
        }
        return $query->getQuery()->getResult();
    }

//return all participant not archived and over 50
    private function findAllQueryRe() : QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where( 'p.archive = :bol')
            ->andWhere('p.note >= :val')
            ->setParameter('bol',false)
            ->setParameter('val',50);

    }
    /**
     * return Query
     */
    public function findAllRe() : Query
    {
      return   $this->findAllQueryRe()->getQuery();
    }

    //return all participant not archived and failed
    private function findFail() : QueryBuilder
    {
        return $this->createQueryBuilder('p')
        ->where( 'p.archive = :bol')
        ->andWhere('p.note < :val')
        ->andWhere('p.note >= :zero')
        ->setParameter('bol',false)
        ->setParameter('val',50)
        ->setParameter('zero',0);
    }

    /**
     *return Query
     */
    public function findAllFail() :Query
    {
        return $this->findFail()->getQuery();
    }

    //return all archived participant
    private function findArch() :QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.archive = :bol')
            ->setParameter('bol', true);
    }

    public function findAllArch() :Query
    {
        return $this->findArch()->getQuery();
    }


    //---------------------------order note ASC ---------------------------------
    public function findAllArchCr() : Query
    {
        return $this->findArch()->orderBy('p.note','ASC')->getQuery();
    }

    public function findAllfailCr () :Query
    {
        return $this->findFail()->orderBy('p.note','ASC')->getQuery();
    }
    public function findAllSuccessCr () :Query
    {
        return $this->findAllQueryRe()->orderBy('p.note','ASC')->getQuery();
    }
    //---------------------------order note DESC ---------------------------------
    public function findAllArchDecr() : Query
    {
        return $this->findArch()->orderBy('p.note','DESC')->getQuery();
    }

    public function findAllfailDecr() : Query
    {
        return $this->findFail()->orderBy('p.note','DESC')->getQuery();
    }
    public function findAllSuccessDecr() : Query
    {
        return $this->findAllQueryRe()->orderBy('p.note','DESC')->getQuery();
    }

    //---------------------------order by quiz ---------------------------------

    public function findAllArchQZ() :Query
    {
        return $this->findArch()->orderBy('p.quiz','DESC')->getQuery();
    }
    public function findAllfailQZ() :Query
    {
        return $this->findFail()->orderBy('p.quiz','DESC')->getQuery();
    }
    public function findAllSuccessQZ() :Query
    {
        return $this->findAllQueryRe()->orderBy('p.quiz','DESC')->getQuery();
    }

    public function findByQuestId($id) :QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->where('u.quiz = :val')
            ->setParameter('val',$id);
    }

    public function ppqnotedesc($id) : Query
    {
        return $this->findByQuestId($id)->orderBy('u.note','DESC')->getQuery();
    }

    public function ppqnoteasc($id) : Query
    {
        return $this->findByQuestId($id)->orderBy('u.note','ASC')->getQuery();
    }

    public function findByUser ($id)
    {
        return $this->createQueryBuilder('u')
            ->where('u.user = :val')
            ->andWhere('u.archive = :bol')
            ->setParameter('val',$id)
            ->setParameter('bol', false);
    }
    public function findbyuserdesc($id)
    {
        return $this->findByUser($id)->orderBy('u.note','DESC')->getQuery();
    }
    public function findbyuserasc($id)
    {
        return $this->findByUser($id)->orderBy('u.note','ASC')->getQuery();
    }
}
