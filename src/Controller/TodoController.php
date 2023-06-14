<?php

namespace App\Controller;

use App\Services\thymobruceClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo", name="todo_")
 */
class TodoController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    private $em;
    private $client;

    public function __construct(EntityManagerInterface $entityManager, thymobruceClient $thymobruceClient)
    {
        $this->em = $entityManager;
        $this->client = $thymobruceClient;
    }

    /**
     * @Route("/add", name="add")
     */
    public function addTodo(Request $request)
    {
        $todoValue = $request->request->get('text');
        if(empty($todoValue))
            return new Response("Todo item not added due to it being empty");

        $todo = [
            'email' => $this->getUser()->getEmail(),
            'name' => "",
            'description' => $todoValue,
        ];
        $todo =  $this->client->createTodo($todo);
        dd($todo);
        return new Response("Added the todo");
    }

}