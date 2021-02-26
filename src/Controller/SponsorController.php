<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\SponsorType;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SponsorController extends AbstractController
{
    /**
     * @Route("/sponsor", name="sponsor")
     */
    public function index(): Response
    {
        return $this->render('sponsor/index.html.twig', [
            'controller_name' => 'SponsorController',
        ]);
    }
    /**
     * @Route ("/AfficherSponsor",name="afficherS")
     */
    public function AfficherSponsor(SponsorRepository $repository)
    {
        $sponsor=$repository->findAll();
        return $this->render('sponsor/afficher.html.twig', [
            'sponsor' => $sponsor
        ]);

    }
    /**
     * @Route("/AjouterSponsor",name="ajouterS")
     */
    public function AjouterEvent(Request $request){
        $sponsor=new Sponsor();
        $form=$this->createForm(SponsorType::class,$sponsor);
        $form->add("Ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($sponsor);
            $em->flush();
            return $this->redirectToRoute("afficherS");
        }
        return $this->render("sponsor/ajouter.html.twig",["f"=>$form->createView()]);

    }
    /**
     * @Route ("/SupprimerSponsor/{id}",name="supprimerS")
     */
    public function SupprimerSponsor(SponsorRepository $repository , $id)
    {
        $sponsor = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($sponsor);
        $em->flush();
        return $this->redirectToRoute("afficherS");
    }
    /**
     * @Route ("/ModifierSponsor/{id}",name="modifierS")
     */
    public function ModifierSponsor(SponsorRepository $repository , Request $request , $id){
        $sponsor=$repository->find($id);
        $form=$this->createForm(SponsorType::class,$sponsor);
        $form->add("Modifier",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficherS");
        }
        return $this->render("sponsor/modifier.html.twig",["f"=>$form->createView()]);
    }
    /**
     * @Route ("/RechercherSponsor",name="rechercherS")
     */
    public function rechercher(SponsorRepository $repository,Request $request){
        $data=$request->get('search');
        $sponsor=$repository->findBy(['nom'=>$data]);
        return $this->render("sponsor/afficher.html.twig", ["sponsor"=>$sponsor]);
    }
}
