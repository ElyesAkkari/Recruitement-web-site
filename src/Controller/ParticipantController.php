<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\AjouterparticipantsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant", name="participant")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }
    public function ajouterparticipant (){
        $participant = new Participants();
        $form = $this->createForm(AjouterparticipantsType::class, $participant); // nom de la form : ajouterformateur

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();

            //return $this->redirectToRoute('les_formateurs'); //route de de la template de l'affichage pour faire un lien avec ajouter
        }

        return $this->render(
            'formateur/ajouterformateur.html.twig',
            array('form' => $form->createView())
        );
    }
}
