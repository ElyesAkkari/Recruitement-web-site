<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Entity\User;
use App\Repository\ParticipationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ParticipationController extends AbstractController
{
    /**
     * @Route("/participation", name="participation")
     */
    public function index(ParticipationRepository $r, PaginatorInterface $paginator, Request $request)
    {  $em=$paginator->paginate($r->findAllRe(),
        $request->query->getInt('page', 1), 8);
        return $this->render('participation/index.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route("/participationback",name="participationback")
     */
    public function indexB(ParticipationRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $em=$paginator->paginate($repository->findAll(),
        $request->query->getInt('page',1),8);
        return $this->render('participation/backindex.html.twig',['em'=>$em]);
    }

    /**
     * @Route ("/failedParticipation", name="failedParticipation")
     */
    public function fail(ParticipationRepository $r, PaginatorInterface $paginator, Request $request)
    {  $em=$paginator->paginate($r->findAllFail(),
            $request->query->getInt('page',1),8);
        return $this->render('participation/fail.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route ("deleteParticipation/{id}", name="deleteParticipation")
     */
    public function deleteRep(Participation $participation, EntityManagerInterface $em){

        $em->remove($participation);
        $em->flush();
        return $this->redirect("/archivedPart");
    }

    /**
     * @Route ("archiveParticipation/{id}", name="archiveParticipation")
     */
    public function archPart(Participation $participation, EntityManagerInterface $em) :Response
    {

        $url= $_SERVER['HTTP_REFERER'];
        $participation->setArchive(true);
        $em->persist($participation);
        $em->flush();
        return $this->redirect($url);
    }

    /**
     * @Route("/archivedPart" , name="archivedPart")
     */
    public function archivedPart(ParticipationRepository $r, PaginatorInterface $paginator, Request $request)
    {  $em=$paginator->paginate($r->findAllArch(), $request->query->getInt('page',1),8);

        return $this->render('participation/archived.html.twig', [
            'em' => $em
        ]);

    }

    /**
     * @Route("recherche", name="recherche")
     */
    public function recherche(ParticipationRepository $repository, Request $request)
    {
        $data=$request->get('search');
        $em=$repository->search($data);

        return $this->render('participation/index.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route ("/archiveCr", name="archiveCr")
     */
    public function archiveCr(ParticipationRepository $repository, PaginatorInterface $paginator,Request $request){
        $em=$paginator->paginate($repository->findAllArchCr(), $request->query->getInt('page',1),8);

        return $this->render('participation/archived.html.twig', [
            'em' => $em
        ]);
    }
    /**
     * @Route ("/archiveDecr",name="archiveDecr")
     */
    public function archiveDecr(ParticipationRepository $repository, PaginatorInterface $paginator,Request $request){
        $em=$paginator->paginate($repository->findAllArchDecr(), $request->query->getInt('page',1),8);

        return $this->render('participation/archived.html.twig', [
            'em' => $em
        ]);
    }
    /**
     * @Route ("/archiveQZ",name="archiveQZ")
     */
    public function archiveQZ(ParticipationRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $em=$paginator->paginate($repository->findAllArchQZ(), $request->query->getInt('page',1),8);

        return $this->render('participation/archived.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route ("/failCr", name="failCr")
     */
    public function failCr(ParticipationRepository $repository, PaginatorInterface $paginator,Request $request){
        $em=$paginator->paginate($repository->findAllfailCr(), $request->query->getInt('page',1),8);
        return $this->render('participation/fail.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route ("/failDecr",name="failDecr")
     */
    public function failDecr(ParticipationRepository $repository, PaginatorInterface $paginator,Request $request){
        $em=$paginator->paginate($repository->findAllfailDecr(), $request->query->getInt('page',1),8);

        return $this->render('participation/fail.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route ("/failQZ",name="failQZ")
     */
    public function failQZ(ParticipationRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $em=$paginator->paginate($repository->findAllfailQZ(), $request->query->getInt('page',1),8);

        return $this->render('participation/fail.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route ("/SuccessCr", name="SuccessCr")
     */
    public function SuccessCr(ParticipationRepository $repository, PaginatorInterface $paginator,Request $request){
        $em=$paginator->paginate($repository->findAllSuccessCr(), $request->query->getInt('page',1),8);
        return $this->render('participation/index.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route ("/SuccessDecr",name="SuccessDecr")
     */
    public function SuccessDecr(ParticipationRepository $repository, PaginatorInterface $paginator,Request $request){
        $em=$paginator->paginate($repository->findAllSuccessDecr(), $request->query->getInt('page',1),8);

        return $this->render('participation/index.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route ("/SuccessQZ",name="SuccessQZ")
     */
    public function SuccessQZ(ParticipationRepository $repository, PaginatorInterface $paginator, Request $request)
    {
        $em=$paginator->paginate($repository->findAllSuccessQZ(), $request->query->getInt('page',1),8);

        return $this->render('participation/index.html.twig', [
            'em' => $em
        ]);
    }

    /**
     * @Route ("/notes/{id}", name="notes")
     */
    public function notes(User $user, ParticipationRepository $repository, PaginatorInterface $paginator, Request $request){
        $em=$paginator->paginate($repository->findByUser($user), $request->query->getInt('page',1),8);
        return $this->render('participation/index2.html.twig' ,
        ['em'=> $em
        ]);
    }

    /**
     * @Route("/notedesc/{id}", name="notedesc")
     */
    public function notedesc (User $user, ParticipationRepository $repository, PaginatorInterface $paginator, Request $request){
        $em=$paginator->paginate($repository->findbyuserdesc($user), $request->query->getInt('page',1),8);
        return $this->render('participation/index2.html.twig' ,
            ['em'=> $em
            ]);
    }

    /**
     * @Route("/noteasc/{id}", name="noteasc")
     */
    public function notedasc (User $user, ParticipationRepository $repository, PaginatorInterface $paginator, Request $request){
        $em=$paginator->paginate($repository->findbyuserasc($user), $request->query->getInt('page',1),8);
        return $this->render('participation/index2.html.twig' ,
            ['em'=> $em
            ]);
    }

    /**
     * @Route ("/imprimer/{id}",name="imprimer")
     */
    public function imprimer (Participation $participation,Request $request, Pdf $knpSnappyPdf)
    {
        $html= $this->renderView('participation/resultpdf.html.twig',["p"=>$participation]);
        $knpSnappyPdf->setOption('margin-bottom',0);
        $knpSnappyPdf->setOption('margin-top',0);
        $knpSnappyPdf->setOption('margin-right',0);
        $knpSnappyPdf->setOption('margin-left',0);
        $knpSnappyPdf->setOption('enable-smart-shrinking',true);
        $knpSnappyPdf->setOption('zoom',1.5);
        return new Response(
            $knpSnappyPdf->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="cv.pdf"'
            )
        );
    }

    /**
     * @Route ("/nonquiz",name="nonquiz")
     */
    public function nonqiuz (ParticipationRepository $r, PaginatorInterface $paginator, Request $request)
    {
        $em = $paginator->paginate($r->findNullQuiz(),
            $request->query->getInt('page', 1), 8);
        return $this->render('participation/nonquiz.html.twig', [
            'em' => $em
        ]);
    }


}
