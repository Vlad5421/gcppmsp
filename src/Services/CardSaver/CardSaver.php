<?php

namespace App\Services\CardSaver;

use App\Entity\Visitor;
use App\Repository\CardRepository;
use App\Repository\VisitorRepository;
use App\Services\CustomParametersBug;
use App\Services\MailService;
use Doctrine\ORM\EntityManagerInterface;

class CardSaver
{
    private CardRepository $card_repo;
    private MailService $mailer;
    private VisitorRepository $visitor_repo;
    private EntityManagerInterface $em;
    private CustomParametersBug $parameters;

    public function __construct
    (
        CardRepository $card_repo,
        MailService $mailer,
        VisitorRepository $visitor_repo,
        EntityManagerInterface $em,
        CustomParametersBug $parameters,
    ) {
        $this->card_repo = $card_repo;
        $this->mailer = $mailer;
        $this->visitor_repo = $visitor_repo;
        $this->em = $em;
        $this->parameters = $parameters;
    }
    public function createVistor($form_data)
    {
        $card = $this->card_repo->find((int) $form_data["card_id"]);
        if ($card)
        {
            //            dd($card);
            $filial = $card->getFilial();
            if (! $this->visitor_repo->findOneByCard($card))
            {
                $visitor = (new Visitor())
                    ->setName($form_data["fullname"])
                    ->setEmail($form_data["email"])
                    ->setPhoneNumber($form_data["phone"])
                    ->setAgeChildren($form_data["age"])
                    ->setReason($form_data["reason"])
                    ->setConsultForm($form_data["formConsultation"])
                    ->setCard($card)
                    ->setConsent($form_data["consent"]);
                $this->em->persist($visitor);

                $card->setUpdatedAt(new \DateTime("now"));
                $this->em->persist($visitor);
                $this->em->flush();

                $man_time = str_pad(intdiv($card->getStart(), 60), 2, "0", STR_PAD_LEFT) . ":" . str_pad($card->getStart() % 60, 2, "0", STR_PAD_LEFT);
                $fromEmail = 'vladislav_ts@list.ru';
                $fromName = 'Психологический центр';
                $date = $card->getDate()->format("d.m.Y");


                $service_name = $card->getService()->getName();
                $many = $card->getService()->getPrice();
                $address = $filial->getAddress();
                $FIO_worker = $card->getSpecialist()->getFIO();
                $visitor_name = $form_data["fullname"];
                $reason = $form_data["reason"];
                $textMail = "Добрый день, консультация назначена!\nФИО: $visitor_name,\nКогда: $date - $man_time (ОМСК),\n" .
                    "Услуга: $service_name,\nСпециалист: $FIO_worker,\nСтоимость: $many,\nАдрес: $address\nТелефн: +7 (3812) 77-77-79.\n\n";
                $visitor_phone = $form_data["phone"];
                $consult_form = $form_data["formConsultation"];
                // ОТправлять ли письмо посетителю
                $fake_visitor_mail = false;
                if ($this->parameters->get("send_mail_visitor"))
                {
                    try {
                        $toEmail = $form_data["email"];
                        $this->mailer->sendMail($fromEmail, $fromName, $toEmail, $textMail);
                    } catch (\Exception $e) {
                        $fake_visitor_mail = true;
                    }

                }
                $children_age = $form_data["age"];
                $textMail = $textMail . "\nУказанный телефон для связи: $visitor_phone,\n" .
                    "Возраст ребенка: $children_age\n" .
                    "Цель (причина): $reason\n" .
                    "Тип консультации: $consult_form\n";
                if ($fake_visitor_mail){
                    $textMail = $textMail . "Посетитель указал не существующий email.";
                }

                $toEmail = $card->getSpecialist()->getEmail();
                $this->mailer->sendMail($fromEmail, $fromName, $toEmail, $textMail);

                return "created";
            } else
            {
                return "non_empty";
            }
        }
        return "non_card";
    }
}
