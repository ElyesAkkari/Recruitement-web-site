<?php

namespace App\Controller;
use App\Entity\Formation;
use App\Entity\Participants;
use App\Entity\User;
use App\Entity\Commantaire;
use App\Form\AjouterparticipantsType;
use App\Form\CommantaireType;
use App\Repository\CommantaireRepository;
use App\Repository\FormationRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ModifierformationType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use symfony\Component\Serializer\annotaion\groups;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;





use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommantaireController extends AbstractController
{
    /**
     * @Route("/commantaire", name="commantaire")
     */
    public function index(): Response
    {
        return $this->render('commantaire/index.html.twig', [
            'controller_name' => 'CommantaireController',
        ]);
    }

    /**
     * @Route("/ajoutercommantaire/{id}", name="commantaire")
     */
    public function ajoutercommantaire(request $request,FormationRepository $repository,$id,FlashyNotifier $flashy)
{
    $formation = $repository->find($id);
    $comment=new Commantaire();
    $form=$this->createForm(CommantaireType::class,$comment);


    $form->handleRequest($request);

    if($form->isSubmitted()&&$form->isValid()){
         $comment->setCreated(new \DateTime());
        $comment->setIdformation($formation);
        $comment->setIduser(new User());





        $em=$this->getDoctrine()->getManager();

        $em->persist($comment);
        $em->flush();
        $flashy->success('votre commentaire est ajouté avec succès !' );

        return $this->redirect('/ajoutercommantaire/'.$id);
    }
    $participant = new Participants();
    $formm = $this->createForm(AjouterparticipantsType::class, $participant); // nom de la form : ajouterformateur

    $formm->handleRequest($request);

    if ($formm->isSubmitted()) {
        $participant->setDate(new \DateTime());
        $participant->setFormation($formation);
        $em = $this->getDoctrine()->getManager();
        $em->persist($participant);
        $em->flush();
        return $this->redirect('/ajoutercommantaire/'.$id);

        //return $this->redirectToRoute('les_formateurs'); //route de de la template de l'affichage pour faire un lien avec ajouter
    }


    return $this->render('commantaire/ajoutercommantaire.html.twig', [
        'formation' => $formation,
        'comment'=>$form->createView(),
        'participant'=>$formm->createView(),
    ]);

}

}




