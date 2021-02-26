<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
    /**
     * @Route ("/AfficherEvent",name="afficherE")
     */
    public function AfficherEvent(EventRepository $repository): Response
    {
        $event=$repository->findAll();
        return $this->render('event/afficher.html.twig', [
            'event' => $event
        ]);

    }
    /**
     * @Route("/AjouterEvent",name="ajouterEvent")
     */
    public function AjouterEvent(Request $request){
        $event=new Event();
        $form=$this->createForm(EventType::class,$event);
        $form->add("Ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute("afficherE");
        }
        return $this->render("event/ajouter.html.twig",["f"=>$form->createView()]);

    }
    /**
     * @Route ("/SupprimerEvent/{id}",name="supprimerE")
     */
    public function SupprimerEvent(EventRepository $repository , $id)
    {
        $event = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute("afficherE");
    }
    /**
     * @Route ("/ModifierEvent/{id}",name="modifierE")
     */
    public function ModifierEvent(EventRepository $repository , Request $request , $id){
        $event=$repository->find($id);
        $form=$this->createForm(EventType::class,$event);
        $form->add("Modifier",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficherE");
        }
        return $this->render("event/modifier.html.twig",["f"=>$form->createView()]);
    }
    /**
     * @Route ("/RechercherEvent",name="rechercherE")
     */
    public function rechercher(EventRepository $repository,Request $request){
        $data=$request->get('search');
        $event=$repository->findBy(['nom'=>$data]);
        return $this->render("event/afficher.html.twig", ["event"=>$event]);
    }
}