<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Societe;
use App\Form\SocieteType;


class SocieteController extends AbstractController
{
    /**
     * @Route("/societe", name="societe")
     */
    public function index(): Response
    {
        return $this->render('societe/index.html.twig', [
            'controller_name' => 'SocieteController',
        ]);
    }


    /**
     * @Route("/inscriptionSociete", name="inscriptionSociete")
     */
    public function inscriptionS(Request $request, EntityManagerInterface $em)
    {
        $societe = new Societe();
        $form=$this->createForm(SocieteType::class,$societe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($societe);
            $em->flush();
            
            return $this->redirectToRoute('login');
        }
        return $this->render('societe/inscriptionSociete.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route ("/login", name="login")
     */
    public function login (){
        return $this->render('societe/login.html.twig');
    }


    /**
     * @Route ("/AfficherSociete",name="afficherSociete")
     */
    public function AfficheSocieteAction()  {
        $societes=$this->getDoctrine()->getRepository(Societe::class)->findAll();

        
        return $this->render('societe/afficher.html.twig', [
            'societes' => $societes
        ]);

    }

    /**
     * @Route ("/UpdateSociete/{id}",name="UpdateSociete")
     */
    public function UpdateSocieteAction(Request $request,$id){

        $em=$this->getDoctrine()->getManager();
        $societe = $em->getRepository(Societe::class)->find($id);
        $form=$this->createForm(SocieteType::class,$societe)
        ->add('Ajouter',SubmitType::class,[
            'label' =>'Save Changes',
            'attr' => ['class' => 'btn btn-primary'],
        ]);
        $checkout=$form->handleRequest($request);
        if($checkout->isSubmitted() &&$checkout->isValid())
        {
            $em->flush();
        }
        return $this->redirectToRoute("afficherSociete");

        return $this->render('societe\modifier.html.twig', array(
            'form'=>$form->createView()

        ));
     }


     /**
     * @Route ("/SupprimerSociete/{id}",name="SupprimerSociete")
     */
    public function SupprimerSocieteAction(Request $request,$id){

        $societe= $this->getDoctrine()->getRepository(Societe::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($societe);
        $em->flush();

        return $this->redirectToRoute("afficherSociete");
     }

}
