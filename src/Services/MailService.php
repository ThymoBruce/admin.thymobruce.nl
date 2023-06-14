<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    private $mailer;
    private $errorlog;
    public function __construct(MailerInterface $mailer, ErrorService $error)
    {
        $this->mailer = $mailer;
        $this->errorlog =$error;
    }

    public function sendMessage($email, $subject, $message)
    {
        $email = (new TemplatedEmail())
                ->from('info@thymobruce.nl')
                ->subject($subject)
                ->to($email)
                ->htmlTemplate('Features/emails/generalMessage.html.twig')
                ->context([
                    'message' => $message,
                    'subject' => $subject,
                ]);

        try {
            $this->mailer->send($email);
            $this->errorlog->EmailErrorLog("Successfully emailed a message ");
            return true;
        }
        catch(\Exception $exception){
            $this->errorlog->EmailErrorLog($exception->getMessage());
            return $exception->getMessage();
        }
    }
}