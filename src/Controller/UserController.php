<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use App\Form\ProfilType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController\Serializable;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\User;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\Session\Session;





class UserController extends AbstractController 
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response  {

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
             
        ]);
    }

    /**
     * @Route("/AjoutUser", name="AjoutUser")
     */

    public function AjoutUserAction(Request $request){
        $user= new User();
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($user); 
            $em->flush();
        
            return $this->redirectToRoute('afficherUser');

        }
    
        return $this->render('user/ajouter.html.twig',array('f'=>$form->createView()));
    }
    
    /**
     * @Route ("/AfficherUser",name="afficherUser")
     */
    public function AfficheUserAction(Request $request,PaginatorInterface $paginator)  {
        $donnes=$this->getDoctrine()->getRepository(User::class)->findAll();


        $users= $paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),4

        );
        
        return $this->render('user/afficher.html.twig', [
            'users' => $users
        ]);

    }

     /**
     * @Route ("/UpdateUser/{id}",name="UpdateUser")
     */
    public function UpdateUserAction(Request $request,$id){

        $em=$this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $form=$this->createForm(UserType::class,$user);
        $checkout=$form->handleRequest($request);
        if($checkout->isSubmitted() &&$checkout->isValid())
        {
            $em->flush();
        }
        return $this->render('user\modifier.html.twig', array(
            'form'=>$form->createView()

        ));
    }


     /**
     * @Route ("/ModifierProfil/{id}",name="ModifierProfil")
     */
    public function ModifierProfilAction(Request $request,$id){

        $em=$this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $form=$this->createForm(ProfilType::class,$user);
        $checkout=$form->handleRequest($request);
        if($checkout->isSubmitted() && $checkout->isValid())

        {
            
            $em->flush();

            $this->addFlash('success','profil modifié avec succès !');
        }
        return $this->render('user\modifierprofil.html.twig', array(
            'form'=>$form->createView()

        ));
    }


     /**
     * @Route ("/SupprimerUser/{id}",name="SupprimerUser")
     */
     public function SupprimerUserAction(Request $request,$id){

        $user= $this->getDoctrine()->getRepository(User::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute("afficherUser");
     }



     /**
     * @Route ("/SupprimerProfil/{id}",name="SupprimerProfil")
     */
    public function SupprimerProfilAction(Request $request,$id){

        $user= $this->getDoctrine()->getRepository(User::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();


        return $this->redirectToRoute("login");
     }

     /**
     * @Route ("/bloquecandidat/{id}",name="bloquecandidat")
     */
    public function bloqueCandidat (Request $request,$id){
        $candidat= $this->getDoctrine()->getRepository(User::class)->find($id);
        $candidat->setIsBlocked(true);  
        $em = $this->getDoctrine()->getManager();
        $em->flush();


        
        
        $this->addFlash('success',' Candidat is blocked !');
        return $this->redirectToRoute('afficherUser');

    }


    /**
     * @Route ("/countCandidat",name="countcandidat")
     */
    public function countCandidat(Request $request){
        $em = $this->getDoctrine()->getManager();

        $repCandidat = $em->getRepository(User::class);

        $totalCandidats = $repCandidat->createQueryBuilder('c')
        ->select('count(c.id)')
        ->getQuery()
        ->getSingleScalarResult();


        return new Response($totalCandidats);

    }

    /**
     * @Route("/delete_user/{id}", name="delete_user")
     */
    public function deleteUser($id){
      $em = $this->getDoctrine()->getManager();
      $usrRepo = $em->getRepository(User::class);

      $user = $usrRepo->find($id);
      $em->remove($user);
      $em->flush();
      $currentUserId = $this->getUser()->getId();
      if ($currentUserId == $id)
      {
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();
      }
    //   $this->addFlash('alert','profil supprimer avec succès !');



      return $this->redirectToRoute('login');

    }
    
}
