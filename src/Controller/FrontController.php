<?php

namespace App\Controller;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use App\Entity\Comment;
use App\Entity\Comments;
use App\Entity\Event;
use App\Entity\Reclamation;
use App\Form\CommentsType;
use App\Form\EventType;
use App\Form\ReclamationType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\SponsorRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/front", name="front")
     */
    public function front(): Response
    {
        return $this->render('testing.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }
    /**
     * @Route("/frontoffice", name="front_office")
     */
    public function index(): Response
     {
         return $this->render('front/indexFront.html.twig', [
             'controller_name' => 'FrontOfficeController',
         ]);
     }

    /**
     * @Route("/frontoffice/AfficherEvent/detail/{id}/", name="detailE")
     */
    public function detail(SponsorRepository $sponsorRepository , EventRepository $repository , $id , Request $request)
     {
         $event=$repository->find($id);
         $sponsor=$sponsorRepository->findAll();
         $comment=new Comments();
         $commentForm=$this->createForm(CommentsType::class,$comment);
         $commentForm->handleRequest($request);
         if ($commentForm->isSubmitted() && $commentForm->isValid()){
             $comment->setcreated_at(new \DateTime());
             $comment->setEvents($event);
             $em=$this->getDoctrine()->getManager();
             $em->persist($comment);
             $em->flush();
             $this->addFlash('message', 'Votre commentaire a été bien envoyé');
             $this->redirectToRoute('detailE',['id'=>
             $event->getId()]);
         }
         return $this->render('front/detailEvent.html.twig', [
             'event' => $event,
             'sponsor'=> $sponsor,
             'com'=>$commentForm->createView()
         ]);
     }

     /**
     * @Route ("/frontoffice/AfficherEvent",name="afficherEE")
     */
    public function AfficherEvent(SponsorRepository $sponsorRepository,EventRepository $repository , Request $request , \Knp\Component\Pager\PaginatorInterface $paginator)
    {
        $event = $repository->findAll();
        $sponsor=$sponsorRepository->findAll();
        $events = $paginator->paginate(
            $event, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );
        return $this->render('front/afficherEvent.html.twig', [
            'event'=> $event,
            'sponsor'=>$sponsor
        ]);
    }

    /**
     * @Route ("/frontoffice/RechercherEvent",name="rechercherEE")
     */
    public function rechercherEvent(EventRepository $repository, Request $request)
    {
        $data = $request->get('search');
        $event = $repository->RecherhceEventParId($data);
        return $this->render("front/afficherEvent.html.twig", ["event" => $event]);
    }

    /**
     * @Route("/frontoffice/map", name="map")
     */
    public function map()
    {
        return $this->render('front/map.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }

    /**
     * @Route ("/frontoffice/AfficherEvent/TriN",name="TrierEventParNom")
     */
    public function triParNom(EventRepository $repository , Request $request){
        $button=$request->get('trier');
        $event=$repository->TriEventParNom($button);
        return $this->render("front/afficherEvent.html.twig", ["event"=>$event]);
    }
    /**
     * @Route ("/frontoffice/AfficherEvent/TriP",name="TrierEventParPrix")
     */
    public function triParPrix(EventRepository $repository , Request $request){
        $button=$request->get('trier');
        $event=$repository->TriEventParPrix($button);
        return $this->render("front/afficherEvent.html.twig", ["event"=>$event]);
    }
    /**
     * @Route ("/frontoffice/AfficherEvent/TriD",name="TrierEventParDate")
     */
    public function triParDate(EventRepository $repository , Request $request){
        $button=$request->get('trier');
        $event=$repository->TriEventParDate($button);
        return $this->render("front/afficherEvent.html.twig", ["event"=>$event]);
    }

    /**
     * @Route ("/frontoffice/pagination",name="pagination")
     */
    public function pagination(EventRepository $repository , Request $request, \Knp\Component\Pager\PaginatorInterface $paginator) // Nous ajoutons les paramètres requis
    {
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $donnees = $repository->findAll();

        $events = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );

        return $this->render('front/pagination.html.twig', [
            'pagination' => $events,

        ]);
    }
    /**
     * @Route("/frontoffice/AjouterReclamation",name="ajouterR")
     */
    public function AjouterReclamation(Request $request){
        $recla=new Reclamation();
        $form=$this->createForm(ReclamationType::class,$recla);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $recla->setcreated_at(new \DateTime());
            $em=$this->getDoctrine()->getManager();
            $em->persist($recla);
            $em->flush();
            return $this->redirectToRoute("afficherEE");
        }
        return $this->render("front/reclamation.html.twig",["f"=>$form->createView()]);

    }
}
