<?php

namespace App\Controller;

use App\Entity\Todo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    private $token;
    private $em;
    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $storage)
    {
        $this->em=$entityManager;
        $this->token = $storage;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        if(empty($this->getUser()))
            return $this->redirectToRoute("login_index");
        $todoList = null;
        if(!empty($this->getUser()))
            $todoList = $this->em->getRepository(Todo::class)->findOneBy(['id' => $this->getUser()->getId()]);
        if(!$todoList)
            $todoList = new Todo();

        return $this->render('Pages/index.html.twig', [
            'todolist' => $todoList,
        ]);
    }
    
}