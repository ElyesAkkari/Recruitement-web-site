<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Offre;
use App\Entity\Participation;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\AjoutRepType;
use App\Form\EditRespType;
use App\Repository\AnswerRepository;
use App\Repository\OffreRepository;
use App\Repository\ParticipationRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * @Route("/answer", name="answer")
     */
    public function index( AnswerRepository $repository, PaginatorInterface $paginator, Request $request): Response
    { $em=$paginator->paginate($repository->findAllAns(), $request->query->getInt('page', 1), 8);
        return $this->render('answer/index.html.twig', [
            'reps' => $em
        ]);
    }

    /**
     * @Route ("/addAnswer", name="addAnswer")
     */
    public function addAnswer (Request $request)
    {$answer= new Answer();
    $form=$this->createForm(AjoutRepType::class, $answer);
    $form->handleRequest($request);
    if ($form->isSubmitted()&&$form->isValid()){
        $em=$this->getDoctrine()->getManager();
        $quest=$answer->getQuest();
        $quest->setNbrRep($quest->getNbrRep()+1);
        $em->persist($answer);
        $em->flush();
        $this->addFlash(
            'success',
            'Reponse ajoutée avec success!'
        );
        return $this->redirect("/answer");

    }

    return $this->render('answer/addRep.html.twig',
                        ['form'=>$form->createView()]
    );
    }

    /**
     * @Route ("editReponse/{id}", name="editReponse")
     */
    public function editRep(Answer $answer, Request $request, EntityManagerInterface $em){

        $form=$this->createForm(EditRespType::class,$answer);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $answer=$form->getData();
            $em->persist($answer);
            $em->flush();
            $this->addFlash(
                'update',
                'Réponse N°:  '.$answer->getId().' modifiée avec success!'
            );
            return $this->redirect("/answer");
        }
        return $this->render('answer/EditRep.html.twig',
        ['form'=>$form->createView()]);
    }

    /**
     * @Route ("deleteReponse/{id}", name="deleteReponse")
     */
    public function deleteRep(Answer $answer, EntityManagerInterface $em){
        $this->addFlash(
            'delete',
            'Reponse N°:  '.$answer->getId().' a été supprimer avec success!'
        );
        $quest=$answer->getQuest();
        if ($quest != null){
        $quest->setNbrRep($quest->getNbrRep()-1);
        }
        $em->remove($answer);
        $em->flush();
        return $this->redirect("/answer");
    }

/**
 *@Route("exemple/{id} ",name="exemple")
 */
public function exemple(Quiz $quiz, QuestionRepository $quest, AnswerRepository $rep)
{
    $questions=$quest->findByQuestId($quiz);
    $reponse=$rep->findAll();
    return $this->render('quiz/exemple.html.twig',[
        'quiz'=>$quiz,
        'reps' => $reponse,
        'question'=>$questions
    ]);
}

/**
 *@Route ("postuler/{id}",name="postuler")
 */
public function postuler (EntityManagerInterface $em,Offre $offre, AnswerRepository $rep, QuizRepository $repository, UserRepository $ur,QuestionRepository $quest, Request $request,ParticipationRepository $participationRepository){
    $user=$ur->findById($request->get('user'));
    $participant=$participationRepository->findExist($request->get('user'), $offre);
    if ($participant != null) {
        $this->addFlash(
            'delete',
            'Vous avez déja postuler dans cette offre'
        );
        $url= $_SERVER['HTTP_REFERER'];
        return $this->redirect($url); }

    $quiz=$repository->findOneByField($offre->getDepartement());
    if ($quiz== null){
        $p = new Participation();
        $p->setAdded(new \DateTime());
        $p->setNote(-1);
        $p->setQuiz(null);
        $p->setUser($user);
        $p->setOffre($offre);
        $em->persist($p);
        $em->flush();
        $url= $_SERVER['HTTP_REFERER'];
        $this->addFlash(
            'success',
            'Postulé avec success'
        );
        return $this->redirect($url);
    }
    else{$questions=$quest->findByQuestId($quiz);
        $reponse=$rep->findAll();
        return $this->render('quiz/exemple.html.twig',[
            'quiz'=>$quiz,
            'reps' => $reponse,
            'question'=>$questions,
            'offre'=>$offre
        ]);}
}

/**
 * @Route ("addmultirep", name="addmultirep")
 */
public function addmultirep(Request $request, QuestionRepository $repository)
    {
        $quest=$repository->findOneById($request->get('quest'));
        for ($i=1;$i<=$quest->getNbrRep();$i++){
            $answer = new Answer();
            $answer->setQuest($quest);
            $answer->setReponse($request->get('rep'.$i));
            $bool=false;
            if($request->get('cb'.$i)==true){$bool=true;}
            $answer->setValid($bool);
            $em=$this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush();
        }
        $this->addFlash(
            'success',
            $quest->getNbrRep().'Reponses ajoutées avec success!'
        );
        return $this->redirect("/answer");
    }
}
