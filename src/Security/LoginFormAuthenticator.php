<?php

namespace App\Security;

use App\Entity\Mail;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Routing\RouterInterface;


class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    private $userRepository;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * LoginFormAuthenticator constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(
                                UserRepository $userRepository,
                                RouterInterface $router,
                                CsrfTokenManagerInterface $csrfTokenManager,
                                UserPasswordEncoderInterface $passwordEncoder,
                                EntityManagerInterface $entityManager
                                )
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }


    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'app_login'
                && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials =  [

            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token')
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $csrfToken = new CsrfToken('authenticate', $credentials['csrf_token']);

        if(!$this->csrfTokenManager->isTokenValid($csrfToken)){

            throw new InvalidCsrfTokenException();

        }

        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }



    public function checkCredentials($credentials, UserInterface $user)
    {
       return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        foreach ($token->getUser()->getRoles() as $role){

            if($role === 'ROLE_ADMIN'){

                return new RedirectResponse($this->router->generate('adminsenso'));

            } else {

                return new RedirectResponse($this->router->generate('user_dashboard'));

            }
        }

    }

    //method call on failure so that it can be redirected to the login pages
    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
    }

}
