<?php

namespace App\Services\UniversalGetData;

use App\Repository\CollectionsRepository;
use App\Repository\FilialRepository;
use App\Repository\FilialServiceRepository;
use App\Repository\ServiceRepository;
use App\Services\CalendarMaker;
use App\Services\ScheduleMaker;
use Symfony\Component\HttpFoundation\Request;

class SpaMaker
{
    private CalendarMaker $calendarMaker;
    private ScheduleMaker $scheduleMaker;
    private ServiceRepository $serviceRepository;
    private FilialRepository $filialRepository;
    private FilialServiceRepository $filSerRepo;
    private CollectionsRepository $colRepo;

    public function __construct(CollectionsRepository $colRepo, CalendarMaker $calendarMaker, ScheduleMaker $scheduleMaker, ServiceRepository $serviceRepository, FilialRepository $filialRepository, FilialServiceRepository $filSerRepo){

        $this->calendarMaker = $calendarMaker;
        $this->scheduleMaker = $scheduleMaker;
        $this->serviceRepository = $serviceRepository;
        $this->filialRepository = $filialRepository;
        $this->filSerRepo = $filSerRepo;
        $this->colRepo = $colRepo;
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

    public function getServicesFromFilial($filial_id)
    {
//        dd($this->filSerRepo->findBy(['filial' => $filial_id]));
        return $this->filSerRepo->findBy(['filial' => $filial_id]);
    }

    public function getCollectionsFilials()
    {
//        dd($this->colRepo->findBy(['type'=>'filial']));
        return $this->colRepo->findBy(['type'=>'filial']);
    }
}