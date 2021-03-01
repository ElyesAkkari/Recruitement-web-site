<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscriptionUser", name="inscriptionUser")
     */
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form=$this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $hash=$encoder->encodePassword($user , $user->getPassword());
            $user->setRoles("candidat");
            $user->setPassword($hash);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }
        return $this->render('security/inscriptionUser.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route ("/login", name="login")
     */
    public function login (){
        return $this->render('security/login.html.twig');
    }

}
