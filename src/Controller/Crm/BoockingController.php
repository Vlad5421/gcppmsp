<?php

namespace App\Controller\Crm;

use App\Entity\Card;
use App\Entity\Service;
use App\Repository\ComplectRepository;
use App\Repository\ServiceRepository;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Services\CalendarMaker;
use App\Services\ScheduleMaker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoockingController extends AbstractController
{
    #[Route('/crm/boocking/service/{service_id}/', name: 'app_crm_boocking')]
    public function index(Request $request, CalendarMaker $calendarMaker, ScheduleMaker $scheduleMaker, $service_id ): Response
    {
        $calendar = $calendarMaker->create($request);
        $filial = 1;


        /** @var Service $service */
        $schedule = $scheduleMaker->create($filial, $service_id, $calendar->date_string);

        return $this->render('crm/boocking/index.html.twig', [
            'countRows' => $calendar->count_rows,
            'calenadar' => $calendar,
            'date' => date('d.m.Y'),
            'schedule' => $schedule,
            'filSer' => ['filial' => $filial, 'service' => $service_id]
        ]);
    }

    #[Route('/crm/boocking/services', name: 'app_crm_boocking_sevices')]
    public function addService(ServiceRepository $serviceRep): Response
    {

        return $this->render('crm/boocking/add-service.html.twig', ['services' => $serviceRep->findAll()]);
    }

    #[Route('/api1/crm/boocking/createcard', name: 'app_api1_crm_boocking_createcard')]
    public function boockingCreate(Request $request,
                                   ComplectRepository $complectRepository,
                                   SessionRepository $sessionR,
                                   UserRepository $specialistR,
                                   EntityManagerInterface $em
    ): Response
    {
        $data = json_decode($request->getContent());

        $complect = $complectRepository->findOneBy(['filial' => $data->filial, 'service' => $data->service]);
        $session = $sessionR->findOneBy(['id'=> $data->time]);
        $specialist = $specialistR->findOneBy(['id'=>$data->spec]);
        $date = new \DateTime(ScheduleMaker::normalsDate($data->date));

        $card = new Card();
        $card
            ->setSession($session)
            ->setSpecialist($specialist)
            ->setComplect($complect)
            ->setDate($date)
        ;


        $em->persist($card);
        $em->flush();

        return $this->json('ok', 201) ;
    }


}
