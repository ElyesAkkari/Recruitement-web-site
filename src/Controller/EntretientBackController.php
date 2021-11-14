<?php

namespace App\Controller;

use App\Entity\Entretient;
use App\Form\EditEntretientFormType;
use App\Form\EntretientFormType;
use App\Repository\EntretientRepository;
use Doctrine\ORM\EntityManagerInterface;
//use Mgilet\NotificationBundle\Manager\NotificationManager;
//use Ob\HighchartsBundle\Highcharts\Highchart;
use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\Entity\NotifiableNotification;
use Mgilet\NotificationBundle\Entity\Notification;
use Mgilet\NotificationBundle\Manager\NotificationManager;
use Mgilet\NotificationBundle\NotifiableInterface;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

class EntretientBackController extends Controller
{
    /**
     * @Route("/entretientBack", name="entretientBack")
     */
    public function index(NotificationManager $manager,EntretientRepository $repository): Response
    {
        $em=$repository->findAll();
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
        $all=$notifiableRepo->findAllForNotifiableId($manager->getNotifiableEntity($this->getUser()));

        // Chart
        $nb_entretien = array();
        for ($i = 1; $i <= 12; $i++) {
            $entretien = $this->getDoctrine()->getManager()->getRepository(Entretient::class)->moisEntretien(
                $i
            );
            array_push($nb_entretien, sizeof($entretien));

        }

        $mois = array(
            'Jan',
            'Fev',
            'Mar',
            'Avr',
            'Mai',
            'Jun',
            'Jui',
            'Aou',
            'Sep',
            'Oct',
            'Nov',
            'Dec',
        );
        $series = array(
            array("name" => "nombre d'entretien",    "data" =>$nb_entretien)
        );
        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        $ob->chart->type('line');
        $ob->chart->height(400);
        $ob->chart->width(800);
        $ob->title->text('Nombre des Entretien passés / mois');
        $ob->xAxis->title(array('text' => "Mois"));
        $ob->yAxis->title(array('text' => "Nombre des entretien"));
        $ob->xAxis->categories($mois);

        $ob->series($series);


        return $this->render('entretient/indexback.html.twig', [ 'entretient'=>$em,
            "notifiableEntity"=>$this->getUser(),"chart"=>$ob,
            "notifiableNotifications"=>$all]);
    }

    /**
     * @Route("/notification_mark_as_seen", name="notification_mark_as_seen")
     */
    public function markAllAsSeenAction(NotificationManager  $manager ,NotifiableInterface $notifiable)
    {
        $manager->markAllAsSeen($notifiable, true);

        return ($this->render('entretient/indexback.html.twig', array("notifiable" => $notifiable)));
    }

    /**
     * @Route("/back", name="back")
     */
    public function Backend(): Response
    {

        return $this->render('entretient/Backend.html.twig ');

    }



    /**
     * @Route ("deleteEntBack/{id}", name="deleteEntBack")
     */
    public function deleteEnt(Entretient $entretient, EntityManagerInterface $em){

        $em->remove($entretient);
        $em->flush();
        return $this->redirect("/entretientBack");
    }

    /**
     * @Route ("/addEntretientBack", name="addEntretientBack")
     */
    public function add (Request $request,\Swift_Mailer $mailer)
    {
        $ent= new Entretient();
        $form = $this->createForm(EntretientFormType::class, $ent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ent);
            $em->flush();
            $this->addFlash('info','Added successfully');


            $this->sendSms($ent->getDatedeb()->format("Y-m-d"),$ent->getRecruteur(),$ent->getNumeroTelephone());
            return $this->redirect("/entretientBack");

        }
        return $this->render("entretient/AddEntBack.html.twig",['form'=>$form->createView()]);
    }


    public function sendSms($date,$recruteur,$tel) {

        $twilio = $this->get('twilio.client');
        $text = "Entretien added  recruteur : ".$recruteur." and date : ".$date;

        $twilio->messages->create($tel, [
            'from'=>'+12392043230', // From a Twilio number in your account
            'body'=>$text]

        );
    }

}
