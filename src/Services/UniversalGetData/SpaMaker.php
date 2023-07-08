<?php

namespace App\Services\UniversalGetData;

use App\Repository\FilialRepository;
use App\Repository\ServiceRepository;
use App\Services\CalendarMaker;
use App\Services\ScheduleMaker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SpaMaker
{
    private CalendarMaker $calendarMaker;
    private ScheduleMaker $scheduleMaker;
    private ServiceRepository $serviceRepository;
    private FilialRepository $filialRepository;

    public function __construct(CalendarMaker $calendarMaker, ScheduleMaker $scheduleMaker, ServiceRepository $serviceRepository, FilialRepository $filialRepository){

        $this->calendarMaker = $calendarMaker;
        $this->scheduleMaker = $scheduleMaker;
        $this->serviceRepository = $serviceRepository;
        $this->filialRepository = $filialRepository;
    }
    public function getCalendarData(
        Request $request, $filial_id, $service_id
    )
    {
        $calendar = $this->calendarMaker->create($request);
        $ScheduleCollections = $this->scheduleMaker->getScheduleCollections($filial_id, $service_id, $calendar->day_of_week, $calendar->date_string);

        return [
            'countRows' => $calendar->count_rows,
            'calenadar' => $calendar,
            'date' => date('d.m.Y'),
            'schedules' => $ScheduleCollections,
            'filSer' => ['filial' => $filial_id, 'service' => $this->serviceRepository->findOneBy(["id" => $service_id])],
            'page' => 'Запись на услугу'
        ];
    }

    public function getFilialsFromCollection($collection_id){
        return $this->filialRepository->findBy(['collection'=>$collection_id]);
    }
}
