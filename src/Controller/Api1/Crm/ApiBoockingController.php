<?php

namespace App\Controller\Api1\Crm;

use App\Entity\Card;
use App\Entity\Service;
use App\Repository\ComplectRepository;
use App\Repository\FilialRepository;
use App\Repository\ServiceRepository;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Services\CalendarMaker;
use App\Services\MailService;
use App\Services\OldScheduleMaker;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ApiBoockingController extends AbstractController
{
    // Создание записи по апи - работает
    #[Route('/api1/crm/boocking/createcard', name: 'app_api1_crm_boocking_createcard')]
    public function boockingCreate(Request $request,
                                   ComplectRepository $complectRepository,
                                   SessionRepository $sessionR,
                                   UserRepository $specialistR,
                                   EntityManagerInterface $em,
                                   MailService $mailer,
    ): Response
    {
        $data = json_decode($request->getContent());

        $complect = $complectRepository->findOneBy(['id' => $data->service]);
        $session = $sessionR->findOneBy(['id'=> $data->time]);
        $specialist = $specialistR->findOneBy(['id'=>$data->spec]);
        $date = new \DateTime(OldScheduleMaker::normalsDate($data->date));

        $card = new Card();
        $card
            ->setSession($session)
            ->setSpecialist($specialist)
            ->setComplect($complect)
            ->setDate($date)
        ;

        $em->persist($card);
        $em->flush();


        $fromEmail = 'vladislav_ts@list.ru';
        $fromName = 'GPMPK';
        $toEmail = $specialist->getEmail();
        $date = $card->getDate()->format("d.m.Y");
        $time = $card->getSession()->getTimeStart();
        $textMail = "Новая запись на $date - $time";

        $mailer->sendMail($fromEmail, $fromName, $toEmail, $textMail);

        return new JsonResponse(["id" => $card->getId()], 201) ;
    }

    #[Route('/api1/crm/services/delite', name: 'app_api1_crm_service_delite'), IsGranted('ROLE_SERVICE_ADMIN')]
    public function serviceDelite(ComplectRepository $repo, EntityManagerInterface $em, Request $request)
    {
        $data = json_decode($request->getContent());

        $complect = $repo->findOneBy(['id'=> $data->del_id]);
        $complect->setDeletedAt(new \DateTime());
        $em->persist($complect);
        $em->flush();

//        try {
//            $repo->remove($service, true);
//        } catch (\Exception $exept) {
//            return new JsonResponse(["exeption" => $exept, 'result' => 'NODELITED'], 304);
////            $this->redirectToRoute('app_admin_service_create');
//        }


        return new JsonResponse(["service" => $complect->getId(), 'result' => 'DELITED'], 200);
    }


}
