<?php

namespace App\Controller\Crm;

use App\Repository\FilialRepository;
use App\Repository\FilialServiceRepository;
use App\Repository\ServiceRepository;
use App\Services\CalendarMaker;
use App\Services\ScheduleMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoockingController extends AbstractController
{
    // Календарь - работает
    #[Route('/booking/filials/{filial_id}/services/{service_id}', name: 'app_booking_calendar')]
    public function index(
        Request               $request,
        CalendarMaker         $calendarMaker,
        ScheduleMaker         $scheduleMaker,
        ServiceRepository     $serviceRepository,
                              $filial_id,
                              $service_id
    ): Response
    {

        $calendar = $calendarMaker->create($request);
        $ScheduleCollections = $scheduleMaker->getScheduleCollections($filial_id, $service_id, $calendar->day_of_week, $calendar->date_string);

        dd ($ScheduleCollections);



        return $this->render("booking/calendar.html.twig", [
            'countRows' => $calendar->count_rows,
            'calenadar' => $calendar,
            'date' => date('d.m.Y'),
            'schedules' => $ScheduleCollections,
            'filSer' => ['filial' => $filial_id, 'service' => $serviceRepository->findOneBy(["id" => $service_id])],
            'page' => 'Запись на услугу'
        ]);
    }

    // Список филиалов - работает
    #[Route('/booking/filials/{collection_id}', name: 'app_booking_filials')]
    public function filialsList(FilialRepository $filRepository, $collection_id ): Response
    {
        $filials = $filRepository->findBy(['collection'=>$collection_id]);
        return $this->render("booking/filials.html.twig", ['filials' => $filials, 'page' => 'Выбор услуги']);
    }

    // Список услуг на филиале - работает
    #[Route('/booking/filials/{filial_id}/services', name: 'app_booking_sevices')]
    public function servicesList(FilialServiceRepository $filSerRepo, $filial_id): Response
    {

        $comlects = $filSerRepo->findBy(['filial' => $filial_id]);
//        dd($comlects);

        return $this->render(
            "booking/services.html.twig",
            [
                'complects' => $comlects,
                'filial_id' => $filial_id,
                'page' => 'Выбор услуги'
            ]
        );
    }


}
