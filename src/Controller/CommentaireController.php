<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Postlike;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\OffreRepository;
use App\Repository\PostlikeRepository;
use Doctrine\Persistence\ObjectManager;
use Egulias\EmailValidator\Warning\Comment;
use http\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CommentaireController extends AbstractController
{

    private $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
    * @Route("/supprimercommentaire/{id}", name="supprimercommentaire")
    */
    public function deletecomm ($id, UrlGeneratorInterface $s ,CommentaireRepository  $repository,EntityManagerInterface $entityManager){
        $comm=$repository->find($id);
        $entityManager->remove($comm);
        $entityManager->flush();
        return $this->redirectToRoute('commentaire', ['id' =>  $this->session->get('offre')]);
    }

    /**
     * @Route ("/post/{id}/like",name="post_like")
     * @param Commentaire $commentaire
     * @param PostlikeRepository $likerepo
     *
     * @return Response
     */
    public function like (Commentaire $commentaire,PostlikeRepository $likerepo,EntityManagerInterface $entityManager):Response
    { $user=$this->getUser();
    if(!$user) return $this->json([
        'code'=> 403,
        'message'=> "unsuthorised"
    ],403);

    if($commentaire->islikedByUser($user)){
        $like=$likerepo->findOneBy([
            'post'=>$commentaire,
            'user'=>$user
        ]);
        $entityManager->remove($like);
        $entityManager->flush();
        return $this->json(
            [
                'code'=> 200,
                'message'=>'like bien supprime',
                'likes'=>$likerepo->count(['post'=>$commentaire])
            ],200

        );
    }
       $like =new Postlike();
       $like->setPost($commentaire)
           ->setUser($user);
       $entityManager->persist($like);
       $entityManager->flush();




        return $this->json(['code'=> 200, 'message'=>'clike bien ajoute','likes'=>$likerepo->count(['post'=>$commentaire])],200);

    }




    /**
     * @Route("/commentaire/{id}", name="commentaire")
     */

    public function AjouterCommentaire(Request $request,$id,CommentaireRepository $repository,OffreRepository $repository2)
    {
        $offre=$repository2->find($id);

        $this->session->set('offre', $id);
        $em=$repository2->find($id);
        $commentaire = new Commentaire();

        $en=$this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findBy(['offre'=>$em]);
        $commentaire->setOffre($em);
        $commentaire->setDatetime(new \DateTime());
        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('commentaire', array('id'=>$id));
        }
        return $this->render('/test/jobdetaille.html.twig', ['form' => $form->createView(), 'formations' => $en,"of"=>$offre
        ]);
    }
    /**
     * @param Request $request
     * @Route("/ModifierCommentaire/{id}",name="modifiercommentaire")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    function modifier(CommentaireRepository $repository,$id,Request $request,OffreRepository $repository2)
    {
        $commentaire=$repository->find($id);
        $id_offre=$commentaire->getOffre();
        $offre=$repository2->find($id_offre);

        $form=$this->createForm(CommentaireType::class,$commentaire);
        $en=$this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findAll();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('commentaire',['id' =>  $this->session->get('offre')]);
        }
        return $this->render('test/jobdetaille.html.twig',
            [
                'form'=>$form->createView(), 'formations'=>$en,"of"=>$offre
            ]
        );
    }
}
