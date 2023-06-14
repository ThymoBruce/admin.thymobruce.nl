<?php

namespace App\Controller;

use App\Services\MailService;
use Doctrine\ORM\EntityManagerInterface;
use http\Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * @Route("/messages", name="message_")
 */
class MessageController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailService $mailer)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
    }

    /**
     * @Route(name="index")
     */
    public function index()
    {
        $contacts[] = [
            'id' => 1,
            'name' => "test",
            'lastMessage' => "dit is een test bericht",
        ];

        $contacts[] = [
            'id' => 2,
            'name' => "Thymo",
            'lastMessage' => "Hallo Thymo, hoe gaat het met je? Hoe voel jij je? Misschien helpt het om even iets leuks te doen",
        ];

        $contact = $this->em->getRepository(\App\Entity\Message::class)->createQueryBuilder('m')
                    ->where('m.isDeleted = false')
                    ->andWhere('m.isSend = false')
                    ->orderBy('m.contact', "ASC")
                    ->groupBy('m.contact')
                    ->getQuery()->getResult();

        $contacts[] = $contact[0];

        return $this->render('Features/Messages/message_index.html.twig', [
           'contacts' => $contacts,
            'modalType' => "contact"
        ]);
    }

    /**
     * @Route("/modal", name="modal")
     */
    public function modal(Request $request){
        $data = $request->request->all();
        if(!empty($data["id"]))
            $messageId = $data["id"];
        $newMessage = $data["newMessage"];
        if($newMessage)
            return new Response();

        if(!empty($messageId))
            $message = $this->getMessage();

        if(empty($message))
            throw new \Exception("Sorry no message found");

        return $message;
    }

    public function createNewMessage()
    {
        $message = new \App\Entity\Message();
        $message->setName("");
        $message->setDescription("");
        $message->setUser($this->getUser()->getId());
        $message->setSubject("");
        $this->em->persist($message);
        try {
            $this->em->flush();
            return new Response($message->getId());
        }
        catch(\Exception $exception){
            return new Response($exception->getMessage());
        }

    }

    public function getMessage($id)
    {
        $message = $this->em->getRepository(\App\Entity\Message::class)->findOneBy(['id' => $id]);
        if($message->getIsDeleted())
            throw new NotFoundResourceException("Sorry no message has been found");
        else return $message;
        return new Response();
    }

    /**
     * @Route("/get-contact", name="get_contact")
     */
    public function getContactEmail(Request $request)
    {
        $messageId = $request->request->get('messageId');
        $message = $this->em->getRepository(\App\Entity\Message::class)->findOneBy(['id' => $messageId]);
        $contact = $message->getContact();

        return new Response($contact->getEmail());
    }

    /**
     * @Route("/send-message",name="send_message")
     */
    public function sendMessage(Request $request)
    {
        $message = $request->request->get('message');
        $subject = $request->request->get('subject');
        $email = $request->request->get('email');

        try {
         $this->mailer->sendMessage($email, $subject, $message);
         return new Response(true);
        }
        catch (\Exception $e){
            dd($e->getMessage());
        }

        return new Response();
    }
}