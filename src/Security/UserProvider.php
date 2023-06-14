<?php

namespace App\Security;

use App\Entity\User;
use App\Services\thymobruceClient;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class UserProvider implements UserProviderInterface
{
    private $client;
    public function __construct(thymobruceClient $client)
    {
        $this->client = $client;
    }

    public function loadUserByUsername($username)
    {
        $login = $this->client->loginByUsername($username);
        $login = json_decode($login);

        $user = new User();
        $user->setEmail($login->email);
        $user->setPassword($login->password);
        $user->setRoles($login->roles);
        $user->setFirstName($login->username);
        $user->setId($login->id);
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User)
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));

        return $this->loadUserByUsername($user->getEmail());
    }

    public function supportsClass(string $class)
    {
        return User::class === $class;
    }

}