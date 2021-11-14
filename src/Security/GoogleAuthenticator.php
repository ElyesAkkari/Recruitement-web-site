<?php


namespace App\Security;

use App\Entity\User; // your user entity
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
	$this->router = $router;
    }

    public function supports(Request $request)
    {
        return $request->getPathInfo() == '/connect/google/check' && $request->isMethod('GET');
    }

    public function getCredentials(Request $request)
    {
        
        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);


        $email = $googleUser->getEmail();
        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $googleUser->getId()]);
        if ($existingUser) {
            return $existingUser;
        }

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

            if(!$user){
                $user= new User();
                $user->setEmail($googleUser->getEmail());
                $user->setUsername($googleUser->getName());
                // $user->setPrenom($googleUser->getName());
                $user->setImageGoogle($googleUser->getAvatar());
                $this->em->persist($user);
                $this->em->flush();
        

            }

               return $user;
    }

    /**
     * @return FacebookClient
     * @return \Knp\OAuth2ClientBundle\Client\OAuthClient
     */
    private function getGoogleClient()
    {
        return $this->clientRegistry
            ->getClient('google');
	}



    /**
     * @param Request $request
     * @param \Symfony\Component\Security\Core\Authenticator\Token\tokenInterface $token
     * @param string $providerKey 
     * 
     * @return void
     * 
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
       //
    }

    /**
     * @param Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     * 
     * @return \Symfony\Component\HttpFoundation\Response|null
     * 
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        //
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     * @param Request $request 
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $authException
     * 
     * @return \Symfony\Component\Httpfoundation\Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login');
    }

    // ...
}
