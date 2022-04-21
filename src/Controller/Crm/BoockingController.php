<?php

namespace App\Controller\Crm;

use App\Services\CalendarMaker;
use App\Services\ScheduleMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoockingController extends AbstractController
{
    #[Route('/crm/boocking', name: 'app_crm_boocking')]
    public function index(Request $request, CalendarMaker $calendarMaker, ScheduleMaker $scheduleMaker): Response
    {
        $calendar = $calendarMaker->create($request);
        $filial = 1;
        $service = 1;


        $schedule = $scheduleMaker->create($filial, $service, $calendar->date_string);



        return $this->render('crm/boocking/index.html.twig', [
            'countRows' => $calendar->count_rows,
            'calenadar' => $calendar,
            'date' => date('d.m.Y'),
            'schedule' => $schedule,
        ]);
    }


}
