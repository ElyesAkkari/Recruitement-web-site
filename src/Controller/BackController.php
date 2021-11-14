<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\PropertySearch;
use App\Entity\Sponsor;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\EventType;
use App\Form\PropertySearchType;
use App\Form\SponsorType;
use App\Form\UserType;
use App\Repository\EventRepository;
use App\Repository\ReclamationRepository;
use App\Repository\SponsorRepository;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BackController extends AbstractController
{
    /**
     * @Route("/backoffice", name="back_office")
     */
    public function index()
    {
        return $this->render('back/indexBack.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }
    /**
     * @Route ("/backoffice/table",name="table")
     */
    public function table(EventRepository $repository , SponsorRepository $repositoryy)
    {
        $event=$repository->findAll();
        $sponsor=$repositoryy->findAll();
        return $this->render('back/table.html.twig', [
            'event' => $event, 'sponsor'=>$sponsor,
        ]);
    }
    /**
     * @Route ("/backoffice/charts",name="charts")
     */
    public function charts()
    {
        return $this->render('back/charts.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    /**
     * @Route ("/backoffice/AfficherEvent",name="afficherE")
     */
    public function AfficherEvent(EventRepository $repository )
    {   $ob = new Highchart();
        $event = $repository->findAll();
        $ob->chart->renderTo('linechart');
        $ob->title->text('Types des events');
        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => false),
            'showInLegend'  => true
        ));
        $offre=$repository->stat1();
        $data =array();
        foreach ($offre as $values)
        {
            $a =array($values['type'],intval($values['nbdep']));
            array_push($data,$a);
        }

        $ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));
        return $this->render('back/afficherEvent.html.twig',[ 'chart' => $ob ,'event' => $event ]);

    }
    /**
     * @Route("/backoffice/AjouterEvent",name="ajouterE")
     */
    public function AjouterEvent(Request $request){
        $event=new Event();
        $form=$this->createForm(EventType::class,$event);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move($this->getParameter('images_directory'),$fileName);

            }catch (FileException $e){
                // ... handle exception !!
            }
            $em=$this->getDoctrine()->getManager();
            $event->setImage($fileName);
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute("afficherE");
        }
        return $this->render("back/ajouterEvent.html.twig",["f"=>$form->createView()]);

    }
    /**
     * @Route ("/backoffice/SupprimerEvent/{id}",name="supprimerE")
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
     * @Route ("/backoffice/ModifierEvent/{id}",name="modifierE")
     */
    public function ModifierEvent(EventRepository $repository , Request $request , $id){
        $event=$repository->find($id);
        $form=$this->createForm(EventType::class,$event);
        $form->add("Modifier",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move($this->getParameter('images_directory'),$fileName);

            }catch (FileException $e){
                // ... handle exception !!
            }
            $em=$this->getDoctrine()->getManager();
            $event->setImage($fileName);
            $em->flush();
           // $message=$translator->trans('Utilisateur modifié avec succés');
            $this->addFlash('message', $message);
            return $this->redirectToRoute("afficherE");
        }
        return $this->render("back/modifierEvent.html.twig",["f"=>$form->createView()]);
    }
    /**
     * @Route ("/backoffice/RechercherEvent",name="rechercherE")
     */
    public function rechercherEvent(EventRepository $repository,Request $request){
        $data=$request->get('search');
        $event=$repository->RecherhceEventParId($data);
        return $this->render("back/afficherEvent.html.twig", ["event"=>$event]);
    }
    /**
     * @Route ("/backoffice/AfficherSponsor",name="afficherS")
     */
    public function AfficherSponsor(SponsorRepository $repository)
    {
        $sponsor=$repository->findAll();
        return $this->render('back/afficherSponsor.html.twig', [
            'sponsor' => $sponsor
        ]);

    }
    /**
     * @Route("/backoffice/AjouterSponsor",name="ajouterS")
     */
    public function AjouterSponsor(Request $request){
        $sponsor=new Sponsor();
        $form=$this->createForm(SponsorType::class,$sponsor);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move($this->getParameter('images_directory'),$fileName);

            }catch (FileException $e){
                // ... handle exception !!
            }
            $em=$this->getDoctrine()->getManager();
            $sponsor->setImage($fileName);
            $em->persist($sponsor);
            $em->flush();
            return $this->redirectToRoute("afficherS");
        }
        return $this->render("back/ajouterSponsor.html.twig",["f"=>$form->createView()]);

    }
    /**
     * @Route ("/backoffice/SupprimerSponsor/{id}",name="supprimerS")
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
     * @Route ("/backoffice/ModifierSponsor/{id}",name="modifierS")
     */
    public function ModifierSponsor(SponsorRepository $repository , Request $request , $id){
        $sponsor=$repository->find($id);
        $form=$this->createForm(SponsorType::class,$sponsor);
        $form->add("Modifier",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move($this->getParameter('images_directory'),$fileName);

            }catch (FileException $e){
                // ... handle exception !!
            }
            $em=$this->getDoctrine()->getManager();
            $sponsor->setImage($fileName);
            $em->flush();
            return $this->redirectToRoute("afficherS");
        }
        return $this->render("back/modifierSponsor.html.twig",["f"=>$form->createView()]);
    }
    /**
     * @Route ("/backoffice/RechercherSponsor",name="rechercherS")
     */
    public function rechercherSponsor(SponsorRepository $repository,Request $request){
        $data=$request->get('search');
        $sponsorrr=$repository->RecherhceSponsorParId($data);
        return $this->render("back/afficherSponsor.html.twig", ["sponsor"=>$sponsorrr]);
    }
    /**
     * @Route("/mailer", name="mailing")
     */
    public function mailer(Request $request , \Swift_Mailer $mailer)
    {
        $form=$this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $contact=$form->getData();
            $message= (new \Swift_Message('Verification évenement'))
                ->setTo($contact['email_receiver'])
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig' , compact('contact')
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->addFlash('message','le message a été bien envoyé');
            return $this->redirectToRoute('afficherE');
        }
        return $this->render('back/mailing.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }


    /**
     * @Route ("/backoffice/AfficherEvent/TriNN",name="TrierEventParNomm")
     */
    public function triParNom(EventRepository $repository , Request $request){
        $button=$request->get('trier');
        $event=$repository->TriEventParNom($button);
        return $this->render("back/afficherEvent.html.twig", ["event"=>$event]);
    }
    /**
     * @Route ("/backoffice/AfficherEvent/TriPP",name="TrierEventParPrixx")
     */
    public function triParPrix(EventRepository $repository , Request $request){
        $button=$request->get('trier');
        $event=$repository->TriEventParPrix($button);
        return $this->render("back/afficherEvent.html.twig", ["event"=>$event]);
    }
    /**
     * @Route ("/backoffice/AfficherEvent/TriDD",name="TrierEventParDatee")
     */
    public function triParDate(EventRepository $repository , Request $request){
        $button=$request->get('trier');
        $event=$repository->TriEventParDate($button);
        return $this->render("back/afficherEvent.html.twig", ["event"=>$event]);
    }
    /**
     * @Route ("/backoffice/AfficherEvent/TriNNN",name="TrierEventParNommm")
     */
    public function triParNomS(SponsorRepository $repository , Request $request){
        $button=$request->get('trier');
        $sponsor=$repository->TriSponsorParNom($button);
        return $this->render("back/afficherSponsor.html.twig", ["sponsor"=>$sponsor]);
    }
    /**
     * @Route ("/backoffice/AfficherEvent/TriTTT",name="TrierEventParTypeee")
     */
    public function triParTypeS(SponsorRepository $repository , Request $request){
        $button=$request->get('trier');
        $sponsor=$repository->TriSponsorParType($button);
        return $this->render("back/afficherSponsor.html.twig", ["sponsor"=>$sponsor]);
    }
    /**
     * @Route ("/backoffice/AfficherEvent/TriEEE",name="TrierEventParEventtt")
     */
    public function triParEventS(SponsorRepository $repository , Request $request){
        $button=$request->get('trier');
        $sponsor=$repository->TriSponsorParEvent($button);
        return $this->render("back/afficherSponsor.html.twig", ["sponsor"=>$sponsor]);
    }
    /**
     * @Route ("/backoffice/AfficherEvent/Filtre",name="FiltrerEventParPrixxx")
     */
    public function filtreParPrix(EventRepository $repository , Request $request){
        $data=$request->get('filtrer');
        $event=$repository->FiltreEventPrix($data);
        return $this->render("back/afficherEvent.html.twig",['event'=>$event]);
    }
    /**
     * @Route ("/backoffice/AfficherReclamation",name="afficherRR")
     */
    public function AfficherReclamation(ReclamationRepository $repository )
    {
        $recla = $repository->findAll();
        return $this->render('back/afficherReclamation.html.twig', [
            'reclamation' => $recla ,
        ]);
    }
    /**
     * @Route ("/backoffice/SupprimerReclamation/{id}",name="supprimerR")
     */
    public function SupprimerReclamation(ReclamationRepository $repository , $id)
    {
        $recla = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($recla);
        $em->flush();
        return $this->redirectToRoute("afficherRR");
    }
    /**
     * @Route ("/backoffice/RechercherReclamation",name="rechercherRR")
     */
    public function rechercherReclamamtion(ReclamationRepository $repository,Request $request){
        $data=$request->get('search');
        $recla=$repository->RechercheReclamationParId($data);
        return $this->render("back/afficherReclamation.html.twig", ["reclamation"=>$recla]);
    }
    /**
     * @Route ("/backoffice/AfficherReclamation/TriRRR",name="TrierReclamationParContenu")
     */
    public function triParContenuR(ReclamationRepository $repository , Request $request){
        $button=$request->get('trier');
        $recla=$repository->TriReclamationParContenu($button);
        return $this->render("back/afficherReclamation.html.twig", ["reclamation"=>$recla]);
    }
    /**
     * @Route ("/backoffice/AfficherReclamation/TriRR",name="TrierReclamationParDate")
     */
    public function triParDateR(ReclamationRepository $repository , Request $request){
        $button=$request->get('trier');
        $recla=$repository->TriReclamationParDate($button);
        return $this->render("back/afficherReclamation.html.twig", ["reclamation"=>$recla]);
    }


    /**
     *  @Route("/static", name="static")
     */


    public function static (EventRepository $repo)
    {

    }
}
