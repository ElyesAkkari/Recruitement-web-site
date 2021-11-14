<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Commentaire;
use App\Entity\Offre;
use App\Form\AjouterType;
use App\Form\CommentaireType;
use App\Form\RechercheType;
use App\Repository\CommentaireRepository;
use App\Repository\EventRepository;
use App\Repository\FormateurRepository;
use App\Repository\FormationRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Offreserch;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;




class OffreController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
    * @Route("/", name="test")
    */
    public function index(OffreRepository $repository, EventRepository $eventRepository, FormationRepository $formationRepository): Response
    {   $ev=$eventRepository->findThree();
        $em=$repository->findThreee();
        $f=$formationRepository->findThree();
        return $this->render('test/index.html.twig ',[
            'em'=>$em,
            'ev'=>$ev,
            'f'=>$f]);

    }
    /**
     * @Route("accueil", name="accueil")
     */
    public function acceuil()
    {
        return $this->redirect('/');

    }
    /**
     * @Route("/back", name="back")
     */
    public function index1(): Response
    {

        return $this->render('test/index1.html.twig ');

    }


    /**
     * @Route("/charts", name="backk")
     */
    public function charts(): Response
    {

        return $this->render('test/charts.html.twig ');
    }
    /**
     * @Route("/affichbackoffre", name="affichbackoffre")
     */
    public function affichbackoffre(OffreRepository $repo): Response


    { $en=$this->getDoctrine()
        ->getManager()
        ->getRepository(Offre::class)
        ->findAll();
        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        $ob->title->text('statistique des offre ');
        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => false),
            'showInLegend'  => true
        ));
        $offre=$repo->stat1();
        $data =array();
        foreach ($offre as $values)
        {
            $a =array($values['departement'],intval($values['nbdep']));
            array_push($data,$a);
        }

        $ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));



        // var_dump($en);
        return $this->render('test/affichoffreback.html.twig ',
            ['offre'=>$en , 'chart' => $ob]);
    }

    /**
     * @param OffreRepository $repository
     * @return Response
     * @route ("/rechercheroffre" , name="rechercheroffre")
     */
    public function rechercheroffre(OffreRepository $repository){
        $nom=$_GET['rechercher'];
        $offres=$repository->rechercheroffre($nom);
        $offre=$repository->stat1();
        return $this->render('test/affichoffreback.html.twig',['offre'=>$offres,'stat'=>$offre]);
    }


    /**
     * @Route("/supprimeroffre/{id}", name="supprimeroffre")
     */
    public function supprimeroffre(Offre $offre,EntityManagerInterface $em)
    {
        $em->remove($offre);
        $em->flush();
        return $this->redirect("/affichbackoffre");
    }

    /**
     * @Route("/affichfrontoffre", name="affichfrontoffre", methods={"GET"})
     */
    public function jobs(Request $request, OffreRepository $offrerep,PaginatorInterface $pagination)
    {
        $en=$this->getDoctrine()
        ->getManager()
        ->getRepository(Offre::class)
        ->findAll();
        $listeoffre=$pagination->paginate(
            $en,
            $request->query->get('page',1 ),2
        );

        // var_dump($en);
        return $this->render('test/jobs.html.twig ',
            ['formations'=>$listeoffre ]);

    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Offre $offre,EntityManagerInterface $em)
    { $part=$offre->getParticipations();
    foreach ($part as $p)
    {$offre->removeParticipation($p);}
        $em->remove($offre);
        $em->flush();
        return $this->redirect("/affichfrontoffre");
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/ajouteroffre", name="ajouteroffre")
     */

        public function ajouter(Request $request)
    {

        $offre= new Offre();
        $form=$this->createForm(AjouterType::class, $offre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {   $em=$this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();

            return $this->redirect("/affichfrontoffre");
        }
        return $this->render('test/ajouteroffre.html.twig',
            ['form'=>$form->Createview()]);
    }
    /**
     *  @Route("/editoffre/{id}", name="editoffre")
     */
    public function editoffre(Offre $offre,Request $request,EntityManagerInterface $em)

    {

        $form= $this->createForm(AjouterType::class,$offre);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {   $classroom = $form->getData();
            $em->persist($offre);
            $em->flush();

            return $this->redirect("/affichfrontoffre");
        }
        return $this->render('test/Editoffre.html.twig',
            ['form'=>$form->Createview()]);
    }


    /**
     * @param Offre $offre
     * @param \Swift_Mailer $mailer
     * @route("/sending/{id}",name="sendmails")
     */
    public function mailing($id,OffreRepository $repo)
    {
        $offre=$repo->find($id);
        return $this->render('Email/OffreTest.html.twig',['offre'=>$offre]);
    }

    /**
     * @param OffreRepository $repository
     * @param \Swift_Mailer $mailer
     * @return Response
     * @Route ("/envoyerEmail/{id}",name="envoyerEmail")
     */
    public function envoyerEmail($id,OffreRepository $repository,\Swift_Mailer $mailer){
        $o = $repository->find($id);
        $message1=$_GET['message'];
        $email = $o->getMailen();
        $message = (new \Swift_Message('envoyer un mail'))
            ->setFrom('malekbenabdellatife123@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView('Email/mail1.html.twig',['message'=>$message1]),
                'text/html'
            );
        $mailer->send($message);
        return $this->redirectToRoute('affichfrontoffre');
    }


    /**
     * @Route("/calendrier", name="calendrier")
     */
    public function calendrier(): Response
    {

        return $this->render('test/calendrier.html.twig ');

    }

    /**
     * @route("/ajoutercalendrier/{id}",name="ajoutercalendrier")
     */
    public function ajoutercalendrier($id)
    {
        $offres = $this->session->get('offres', []);

        $offres = \array_diff($offres, [$id]);

        $offres[] = $id;
        $this->session->set('offres', $offres);
        $offres = $this->session->get('offres', []);

        return $this->json(['offres'=>$offres],200);
    }


}
