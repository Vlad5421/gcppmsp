<?php

namespace App\Controller\Crm;

use App\Entity\User;
use App\Entity\Visitor;
use App\Form\VisitorFormType;
use App\Repository\CardRepository;
use App\Services\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitorController extends AbstractController
{
    #[Route('/crm/visitor/{card_id}', name: 'app_crm_visitor_create')]
    public function create(EntityManagerInterface $em,
                           Request $request,
                           $card_id,
                           CardRepository $cardR,
                           MailService $mailer,
    ): Response
    {
        $card = $cardR->findOneBy(['id'=>$card_id]);
        /** @var User $specialist */
        $specialist= $card->getSpecialist();


        $form = $this->createForm(VisitorFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var Visitor $visitor */

            $visitor = $form->getData();
            $visitorMail = $form->get("email")->getViewData();
            $visitor->setCard($card);

            $em->persist($visitor);
            $em->flush();
                    $fromEmail = 'vladislav_ts@list.ru';
                    $fromName = 'GPMPK';
                    $date = $card->getDate()->format("d.m.Y");
                    $time = $card->getStart();

                    $toEmail = $specialist->getEmail();
                    $textMail = "Новая запись на $date - $time";
                    $mailer->sendMail($fromEmail, $fromName, $toEmail, $textMail);
                    $toEmail = $visitorMail;
                    $mailer->sendMail($fromEmail, $fromName, $toEmail, $textMail);

            $this->addFlash('flash_message', "Вы записаны на консультацию: ". $card->getDate()->format("d.m.Y") . ", " .intdiv($card->getStart(), 60) . ":" .$card->getStart()%60);

            return $this->redirectToRoute('app_home');

        }

        return $this->render('crm/visitor/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Заполнение формы записи'
        ]);
    }
}
