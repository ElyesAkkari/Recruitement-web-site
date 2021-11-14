<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\ResetPassType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;





class SecurityController extends AbstractController
{
    /**
     * @Route("/inscriptionUser", name="inscriptionUser")
     */
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer){
        $candidat = new User();
        $form=$this->createForm(RegisterType::class,$candidat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $hash=$encoder->encodePassword($candidat , $candidat->getPassword());
            $candidat->setPassword($hash);
            $candidat->setActivationToken(md5(uniqid()));
            $candidat->setIsBlocked(false);
            $em->persist($candidat);
            $em->flush();
            $message =(new \Swift_Message('Actvation de votre compte'))
            ->setFrom('no_reply@impacteers.tn')
            ->setTo($candidat->getEmail())
            ->setBody(
                $this->renderView(
                    'security/activation.html.twig',['token' => $candidat->getActivationToken()]
                ),
                'text/html'
            );

            $mailer->send($message);

            $this->addFlash('message','Vous avez bien crée votre compte vous devez confirmer votre @ mail');
            
            // return $this->redirectToRoute('login');
        }
        
        return $this->render('security/inscriptionUser.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
    * @Route("/activation/{token}", name="activation")
    */
    public function activation($token, UserRepository $userrep){

        $candidat = $userrep->findOneBy(['activation_token'=> $token]);

        // if(!$candidat){
        //     throw $this->createNotFoundException('Cette candidat n\'existe pas ');
        // }

        $candidat->setActivationToken(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($candidat);
        $em->flush();


        $this->addFlash('message','Vous avez bien activé votre compte');
        return $this->redirectToRoute('login');


    }



  //  "<p>Bonjour</p> ,<p> Une demande de réinitialisation de mot de passe a été effectuée pour notre site Impacteers . veuillez cliquer sur le lien suivant " . $url ."</p>",
    //           'text/html'
    /**
     * @Route("/forgot_password", name="forgot_password")
    */
    public function forgotPass(Request $request,UserRepository $userrep,\Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator ){

        $form = $this->createForm(ResetPassType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();
            
            $candidat= $userrep->findOneByEmail($data['email']);

            if(!$candidat){
                $this->addFlash('danger', 'Cette adresse n\'existe pas ');

               return $this->redirectToRoute('login');
            }

            $token= $tokenGenerator->generateToken();

            try{
                $candidat->setResetToken($token);
                $em =$this->getDoctrine()->getManager();
                $em->persist($candidat);
                $em->flush();

                $url= $this->generateUrl('reset_password',['token' =>$token],
            UrlGeneratorInterface::ABSOLUTE_URL
            );

                $message =(new \Swift_Message('Actvation de votre compte'))
                ->setFrom('no_reply@impacteers.tn')
                ->setTo($candidat->getEmail())
                ->setBody(
                    "<p>Bonjour</p> ,<p> Une demande de réinitialisation de mot de passe a été effectuée pour notre site Impacteers . veuillez cliquer sur le lien suivant " . $url ."</p>",  
                    'text/html'
                );
    
                $mailer->send($message);
            }catch(\Exception $e){
                $this->addFlash('warning','Une erreur est survenue: ' . $e->getMessage());
                return $this->redirectToRoute('login');

            }

          

            $this->addFlash('message', 'Un e-mail de réinisialisation de mot de passe vous a été envoyé ');

            return $this->redirectToRoute('login');


        }

        return $this->render('security/forgot_pass.html.twig', ['form' => $form->createView()]);

    }



    /**
     * @Route("/reset_password/{token}", name="reset_password")
    */
    public function resetPassword($token, Request $request,UserPasswordEncoderInterface $passwordEncoder){

        $candidat = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);

        if(!$candidat){
            $this->addFlash('danger','Token inconnu');
            return $this->redirectToRoute('login');
        }

        if($request->isMethod('POST')){
            $candidat->setResetToken(null);

            $candidat->setPassword($passwordEncoder->encodePassword($candidat,$request->request->get('password')));
            $em = $this->getDoctrine()->getManager();
            $em->persist($candidat);
            $em->flush();
            $this->addFlash('message','Mot de passe modifié avec succés');

            return $this->redirectToRoute('login');
        }else{
            return $this->render('security/reset_password.html.twig', ['token'=> $token]);
        }
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response {
        // if ($this->getUser()->getActivationToken() != null ) {
        //     return $this->redirectToRoute('login');
        // }

        // if($this->getUser()->getIsBlocked() == true){
            
        //      throw $this->createNotFoundException('Cette candidat n\'existe pas ');

        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

}
