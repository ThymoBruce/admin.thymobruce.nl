<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class Authenticator extends AbstractLoginFormAuthenticator
{
    private $provider;
    private $email;
    private $pass;
    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    public function setEmail($email)
    {
        return $this->email = $email;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request)
    {
        return new Passport(
            new UserBadge($this->email, function (){
                return $this->provider->loadUserByUsername($this->email);
            }),
            new PasswordCredentials($this->pass)

        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // TODO: Implement getUser() method.
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
    }

    public function getLoginUrl(Request $request): string
    {
        //Todo implement getLoginUrl method
    }

    public function supportsRememberMe()
    {
        // TODO: Implement supportsRememberMe() method.
    }

    public function getCredentials(Request $request)
    {

    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        return new Response(true);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // TODO: Implement onAuthenticationFailure() method.
    }
}