<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\AddQuizType;
use App\Form\EditQuizType;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz", name="quiz")
     */
    public function index(QuizRepository $repository,PaginatorInterface $paginator, Request $request): Response
    {$em=$paginator->paginate($repository->findAllQuiz(),
        $request->query->getInt('page', 1), 8);
        return $this->render('quiz/index.html.twig', [
            'quizs' => $em,
        ]);
    }

    /**
     * @Route ("/addQuiz", name="addQuiz")
     */
    public function addQuiz (Request $request) :Response
    {
        $quiz = new Quiz();
        $quiz->setNbrQuest(0);
        $form = $this->createForm(AddQuizType::class, $quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quiz);
            $em->flush();
            $this->addFlash(
                'success',
                'QUIZ ajouté avec success!'
            );
            return $this->redirect("/quiz");

        }
        return $this->render('quiz/addQuiz.html.twig',
            ['form'=>$form->createView() ]
        );
    }


    /**
     * @Route ("/editQuiz/{id}", name="editQuiz")
     */
    public function editQuiz(Quiz $quiz, Request $request, EntityManagerInterface $em){

        $form=$this->createForm(EditQuizType::class,$quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $answer=$form->getData();
            $em->persist($quiz);
            $em->flush();
            $this->addFlash(
                'update',
                'QUIZ N°:  '.$quiz->getId().' modifié avec success!'
            );
            return $this->redirect("/quiz");
        }
        return $this->render('quiz/EditQuiz.html.twig',
            ['form'=>$form->createView()]);
    }

    /**
     * @Route ("/deleteQuiz/{id}", name="deleteQuiz")
     */
    public function deleteQuiz(Quiz $quiz, EntityManagerInterface $em){
        $questions=$quiz->getQuestions();
        foreach ($questions as $q){
            $quiz->removeQuestion($q);
        }
        $this->addFlash(
            'delete',
            'QUIZ N°:  '.$quiz->getId().' a été supprimer avec success!'
        );
        $em->remove($quiz);
        $em->flush();
        $this->addFlash(
            'delete',
            'QUIZ N°:  '.$quiz->getId().' a été supprimer avec success!'
        );
        return $this->redirect("/quiz");
    }

    /**
     * @Route ("/backquiz", name="backquiz")
     */
    public function backquiz(QuizRepository $repository,PaginatorInterface $paginator, Request $request): Response
    {$em=$paginator->paginate($repository->findAllQuiz(),
        $request->query->getInt('page', 1), 8);
        return $this->render('quiz/backindex.html.twig', [
            'quizs' => $em,
        ]);
    }

    /**
     *@Route("backexemple/{id} ",name="backexemple")
     */
    public function exemple(Quiz $quiz, QuestionRepository $quest, AnswerRepository $rep)
    {
        $questions=$quest->findByQuestId($quiz);
        $reponse=$rep->findAll();
        return $this->render('quiz/backexemple.html.twig',[
            'quiz'=>$quiz,
            'reps' => $reponse,
            'question'=>$questions
        ]);
    }

}
