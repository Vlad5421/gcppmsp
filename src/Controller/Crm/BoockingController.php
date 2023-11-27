<?php

namespace App\Controller\Crm;

use App\Services\UniversalGetData\SpaMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoockingController extends AbstractController
{
    // Календарь - работает
    #[Route('/booking/filials/{filial_id}/services/{service_id}', name: 'app_booking_calendar')]
    public function index(SpaMaker $spaMaker, Request $request, $filial_id, $service_id ): Response
    {
        $data = $spaMaker->getCalendarData($request, $filial_id, $service_id);
//        dd($data);
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
    public function servicesList(SpaMaker $spaMaker, $filial_id): Response
    {
        return $this->render(
            "booking/services.html.twig",
            [
                'complects' => $spaMaker->getServicesFromFilial($filial_id),
                'filial_id' => $filial_id,
                'page' => 'Выбор услуги'
            ]
        );
    }


}
