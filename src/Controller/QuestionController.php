<?php

namespace App\Controller;


use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\AddQuestType;
use App\Form\EditQuestType;
use App\Repository\ParticipationRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/question", name="question")
     */
    public function index(QuestionRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
       $em=$paginator->paginate($repository->findAllQuest(), $request->query->getInt('page', 1), 8);
       return $this->render('question/index.html.twig',[ 'questions'=>$em]);
    }

    /**
     * @Route ("/addQuest", name="addQuest")
     */
    public function addQuest (Request $request)
    {
        $question = new Question();
        $form = $this->createForm(AddQuestType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $qz=$question->getQuiz();
            $qz->setNbrQuest($qz->getNbrQuest()+1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            $this->addFlash(
                'success',
                'Question ajouté avec success!'
            );
            return $this->render("answer/addMultiRep.html.twig", ['quest'=>$question]);

        }
        return $this->render("question/AddQuestion.html.twig",['form'=>$form->createView()]);
    }

    /**
     * @Route ("editQuest/{id}", name="editQuest")
     */
    public function editQuest(Question $question, Request $request, EntityManagerInterface $em){

        $form=$this->createForm(EditQuestType::class,$question);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $answer=$form->getData();
            $em->persist($question);
            $em->flush();
            $this->addFlash(
                'update',
                'Question N°:  '.$question->getId().' modifié avec success!'
            );
            return $this->redirect("/question");
        }
        return $this->render('question/EditQuestion.html.twig',
            ['form'=>$form->createView()]);
    }

    /**
     * @Route ("deleteQuest/{id}", name="deleteQuest")
     */
    public function deleteQuest(Question $question, EntityManagerInterface $em){
        $answer=$question->getAnswers();
        $qz=$question->getQuiz();
        if ($qz != null) {
            $qz->setNbrQuest($qz->getNbrQuest() - 1);
        }
        foreach ($answer as $a)
        {$question->removeAnswer($a);}
        $this->addFlash(
            'delete',
            'Question N°:  '.$question->getId().' a été supprimer avec success!'
        );
        $em->remove($question);
        $em->flush();
        return $this->redirect("/question");
    }
    /**
     * @Route ("/questans/{id}", name="questans")
     */
    public function test (Quiz $quiz,QuizRepository $quizRepository, EntityManagerInterface $em, QuestionRepository $repository){
        $quet=$repository->findByQuestId($quiz->getId());
        return $this->render( 'question/quest.html.twig', [
            'questions' => $quet,
            'nomquiz'=>$quiz->getTitre()
        ]);

    }


// liste des participant par quiz + trie croissant et decroissant
    /**
     * @Route ("/listpart/{id}", name="listpart")
     */
    public function listpart (Quiz $quiz, ParticipationRepository $repository, PaginatorInterface $paginator, Request $request){
        $em=$paginator->paginate($repository->findByQuestId($quiz), $request->query->getInt('page', 1), 8);
        return $this->render( 'quiz/index1.html.twig', [
            'em' => $em,
            'nomquiz'=>$quiz->getTitre(),
            'quiz'=>$quiz
        ]);
    }

    /**
     * @Route ("/ppqnotdesc/{id}",name="ppqnotedesc")
     */
    public function ppqnotedesc (Quiz $quiz, ParticipationRepository $repository, PaginatorInterface $paginator, Request $request){
        $em=$paginator->paginate($repository->ppqnotedesc($quiz), $request->query->getInt('page', 1), 8);
        return $this->render( 'quiz/index1.html.twig', [
            'em' => $em,
            'nomquiz'=>$quiz->getTitre(),
            'quiz'=>$quiz
        ]);
    }

    /**
     * @Route ("/ppqnoteasc/{id}",name="ppqnoteasc")
     */
    public function ppqnoteasc (Quiz $quiz, ParticipationRepository $repository, PaginatorInterface $paginator, Request $request){
        $em=$paginator->paginate($repository->ppqnoteasc($quiz), $request->query->getInt('page', 1), 8);
        return $this->render( 'quiz/index1.html.twig', [
            'em' => $em,
            'nomquiz'=>$quiz->getTitre(),
            'quiz'=>$quiz
        ]);
    }
}
