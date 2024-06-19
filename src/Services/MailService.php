<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailService
{
    private MailerInterface $mailer;
    private ParameterBagInterface $params;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->params = $params;
    }


    public function sendMail(
//        $fromEmail,
        $fromName, $toEmail, $textMail)
    {
        $fromEmail = $this->params->get('mail_sender');
        $email = (new Email())
            ->from(new Address($fromEmail, $fromName))
            ->to($toEmail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Почта от гпмпк')
            ->text($textMail)
        ;
        $this->mailer->send($email);
    }
}
