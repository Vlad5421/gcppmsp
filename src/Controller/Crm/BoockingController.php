<?php

namespace App\Controller\Crm;

use App\Repository\FilialRepository;
use App\Repository\FilialServiceRepository;
use App\Repository\ServiceRepository;
use App\Services\CalendarMaker;
use App\Services\ScheduleMaker;
use App\Services\UniversalGetData\SpaMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class BoockingController extends AbstractController
{
    // Календарь - работает
    #[Route('/booking/filials/{filial_id}/services/{service_id}', name: 'app_booking_calendar')]
    public function index(SpaMaker $spaMaker, Request $request, $filial_id, $service_id ): Response
    {
        $data = $spaMaker->getCalendarData($request, $filial_id, $service_id);
        return $this->render("booking/calendar.html.twig", $data);
    }

    // Список филиалов - работает
    #[Route('/booking/filials/{collection_id}', name: 'app_booking_filials')]
    public function filialsList(SpaMaker $spaMaker, $collection_id ): Response
    {
        return $this->render("booking/filials.html.twig", ['filials' => $spaMaker->getFilialsFromCollection($collection_id), 'page' => 'Выбор услуги']);
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
