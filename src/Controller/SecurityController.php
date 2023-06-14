<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\Authenticator;
use App\Security\UserProvider;
use App\Services\thymobruceClient;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\AuthenticatorBundle\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class SecurityController extends AbstractController
{
    private $em;
    private $tokenInterface;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $storage)
    {
        $this->em = $em;
        $this->tokenInterface = $storage;
    }

    /**
     * @Route("/login",name="login_index")
     */
    public function login(AuthenticationUtils $utils, Request $request)
    {
        $this->tokenInterface->setToken(null);
        $error = $utils->getLastAuthenticationError();
        if ($error) {
            $message = $error->getMessage();
            if ($message == 'Bad credentials.')
                $message = 'Onjuiste inlog gegevens';
        }
        return $this->render('Features/Security/login.html.twig', [
            'error' => $error,
        ]);
    }

    /**
     * @Route("/check", name="login_check")
     */
    public function checklogin(Request $request, thymobruceClient $client, UserAuthenticatorInterface $userAuthenticator, Authenticator $authenticator)
    {
        $email = $request->request->get('_username');
        $pass = $request->request->get('_password');
        $user = $client->login(['password' => $pass, 'email' => $email]);
        if($user != '"Bad credentials"'){
            $login = json_decode($user);
            $user = new User();
            $user->setEmail($login->email);
            $user->setPassword($login->password);
            $user->setRoles($login->roles);
            $user->setFirstName($login->username);
            $user->setId($login->id);
            $userAuthenticator->authenticateUser($user, $authenticator, $request);
        }
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        $this->tokenInterface->setToken(null);
        return $this->redirectToRoute("login_index");
    }
}