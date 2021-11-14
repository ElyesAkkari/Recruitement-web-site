<?php

namespace App\Controller;

use App\Entity\Entretient;
use App\Form\EditEntretientFormType;
use App\Form\EntretientFormType;
use App\Repository\EntretientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Mgilet\NotificationBundle\Manager\NotificationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntretientController extends AbstractController
{
    /**
     * @Route("/entretient", name="entretient")
     */
    public function index(EntretientRepository $repository): Response
    {
        $em=$repository->findAll();
        return $this->render('entretient/index.html.twig', ['entretient'=>$em]);
    }

    /**
     * @Route("/entretienClient", name="entretienClient")
     */
    public function index1(EntretientRepository $repository): Response
    {
        $em=$repository->findAll();
        return $this->render('entretient/index1.html.twig', ['entretient'=>$em]);
    }
    /**
     * @Route ("/addEntretient", name="addEntretient")
     */
    public function addQuest (NotificationManager $manager,Request $request,\Swift_Mailer $mailer)
    {
        $ent= new Entretient();
        $form = $this->createForm(EntretientFormType::class, $ent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ent);
            $em->flush();
            $notif = $manager->createNotification(
                 $this->getUser()->getUsername());
            $notif->setMessage("un nouveau entretien été ajouté ");
            $notif->setLink($this->redirectToRoute('entretient'));
            // or the one-line method :

            // you can add a notification to a list of entities
            // the third parameter `$flush` allows you to directly flush the entities
            $manager->addNotification(array($this->getUser()), $notif, true);





            $this->addFlash('info', 'Envoyer réclamation avec succés !');
            return $this->redirect("/entretient");

        }
        return $this->render("entretient/AddEnt.html.twig",['form'=>$form->createView()]);
    }



        /*        //get an instance of \Service_Twilio
                $otherInstance = $twilio->createInstance('BBBB', 'CCCCC');
                return new Response($message->sid);*/


    public function markAllAsSeenAction($notifiable)
    {
        $manager = $this->get('mgilet.notification');
        $manager->markAllAsSeen(
            $manager->getNotifiableInterface($manager->getNotifiableEntityById($notifiable)),
            true
        );

        return ($this->render('@ServiceApresVente/Admin/readReclamation.html.twig', array("notifiable" => $notifiable)));
    }

    /**
     * @Route ("editEnt/{id}", name="editEnt")
     */
    public function editQuest(Entretient $entretient, Request $request, EntityManagerInterface $em){

        $form=$this->createForm(EditEntretientFormType::class,$entretient);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $answer=$form->getData();
            $em->persist($entretient);
            $em->flush();

            return $this->redirect("/entretient");
        }
        return $this->render('entretient/Editent.html.twig',
            ['form'=>$form->createView()]);
    }

    /**
     * @Route ("deleteEnt/{id}", name="deleteEnt")
     */
    public function deleteEnt(Entretient $entretient, EntityManagerInterface $em){

        $em->remove($entretient);
        $em->flush();
        return $this->redirect("/entretient");
    }



    /**
     * @param EntretientRepository $repository
     * @param Request $request
     * @return Response
     * @Route ("/entretient/recherche",name="recherche")
     */
   public function Recherche(EntretientRepository $repository,Request $request){
        $data=$request->get('search');
        $em=$repository->search($data);

        return $this->render('entretient/index.html.twig',['entretient'=>$em]);
    }
    /**
     * @param EntretientRepository $repository
     * @param \Swift_Mailer $mailer
     * @return Response
     * @Route ("/envoyerEmail/{id}",name="envoyerEmail")
     */
    public function envoyerEmail($id,EntretientRepository  $repository,\Swift_Mailer $mailer){

        $e = $repository->find($id);
        $email = $e->getMail();

        $message = (new \Swift_Message('envoyer un mail'))
            ->setFrom('ayadimariem27@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView('Email/mail.html.twig',['entretient'=>$e]),
                'text/html'
            );
        $mailer->send($message);
        return $this->redirectToRoute('entretient');
    }


    /************************SMS BUNDLE***********************************/

}
