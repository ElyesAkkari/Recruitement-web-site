<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Repository\OffreRepository;
use App\Repository\ParticipationRepository;
use App\Repository\QuizRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{



    /**
     * @Route ("/quiznote", name="quiznote")
     */
    public function quiznote (Request $request, UserRepository $ur,OffreRepository $offreRepository,EntityManagerInterface $em,QuizRepository $qr, ParticipationRepository $pr)
    { $note=0;
      $i=0;
     // $d=$request->get('d');
      $d = new \DateTime() ;
      $day=(int)$request->get('d');
      $m=(int)$request->get('m');
      $y=(int)$request->get('y');
      $oid=$request->get('offre');
      $d->setDate($y,$m,$day);
      $numr=$request->get('numr');
      $qid=$request->get('quiz');
      $uid=$request->get('user');
      $q=$request->get('quest');
      $user=$ur->findById($uid);
      $quiz=$qr->findById($qid);
      $offre=$offreRepository->findById($oid);
      do{
          if ($request->get('flexRadio'.$i)==1) {$note++;}
          $i++;
      } while($i<$numr);
      $x=($note * 100)/$q;
      $p = new Participation();
      $p->setAdded($d);
      $p->setNote($x);
      $p->setQuiz($quiz);
      $p->setOffre($offre);
      $p->setUser($user);
      $em->persist($p);
      $em->flush();

      return $this->render("participation/result.html.twig",["p"=>$p , "numr"=>$x]);


    }

}
