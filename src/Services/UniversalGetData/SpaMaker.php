<?php

namespace App\Services\UniversalGetData;

use App\Entity\Card;
use App\Entity\Collections;
use App\Entity\Filial;
use App\Entity\Service;
use App\Repository\CardRepository;
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
    private CardRepository $card_repo;
    private array $collections;
    private int $level = 0;

    public function __construct(CardRepository $card_repo, CollectionsRepository $colRepo, CalendarMaker $calendarMaker, ScheduleMaker $scheduleMaker, ServiceRepository $serviceRepository, FilialRepository $filialRepository, FilialServiceRepository $filSerRepo){

        $this->calendarMaker = $calendarMaker;
        $this->scheduleMaker = $scheduleMaker;
        $this->serviceRepository = $serviceRepository;
        $this->filialRepository = $filialRepository;
        $this->filSerRepo = $filSerRepo;
        $this->colRepo = $colRepo;
        $this->card_repo = $card_repo;
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
    public function getFilialsFromService($service_id){
        $fss = $this->filSerRepo->findBy(['service'=>$service_id]);
        $filials = [];
        foreach ($fss as $fs){
            $filials[] = $fs->getFilial();
        }
        return $filials;
    }

    public function getServices($filial_id = null)
    {
//        dd($this->filSerRepo->findBy(['filial' => $filial_id]));
        return $filial_id ? $this->filSerRepo->findBy(['filial' => $filial_id]) : $this->serviceRepository->findAll();
    }

    public function getCollectionsFilials()
    {
//        dd($this->colRepo->findBy(['type'=>'filial']));
        return $this->colRepo->findBy(['type'=>'filial', 'collection' => null]);
    }

    public function getCollections(null|int $parrent)
    {
        if ($parrent){
            $par_col = $this->colRepo->find($parrent);
            return $this->colRepo->findBy(['collection' => $par_col, 'type'=>'filial', ]);
        }
        return $this->getCollectionsFilials();
    }

    public function getNoEmptyCards(\DateTimeInterface $cur_time): array
    {
        dd($this->card_repo->findNoEmpty($cur_time));
        return [];
    }

    public function getFilialsFromCollectionAndService($collection_id, ?int $service_id)
    {
        $filials = $this->filialRepository->findBy(["collection"=>$collection_id]);
        if (!$service_id){
            return $filials;
        }
        $service = $this->serviceRepository->find($service_id);
        return $this->getTrueServiceFilials($service, $filials);
    }

    public function getCollectionsFromServiceOrParrent(?int $service_id, ?int $parrent): array|null
    {
        if (!$service_id){
            return $this->colRepo->findBy(["collection"=>$parrent]);;
        }
        $service = $this->serviceRepository->find($service_id);
        $collections = $this->colRepo->findBy(["collection"=>$parrent]);

        $colls = [];

        /** @var Collections $collection */
        foreach ($collections as $collection){
            $re = $this->checkCollection($service, $collection);
            if ($re){
                $colls[] = $collection;
            }
        }

        return $colls;
    }
    public function checkCollection(Service $service, Collections $collection)
    {
        $fils = $collection->getFilials()->toArray();
        if (count($fils) > 0 && $this->getTrueServiceFilials($service, $fils) > 0 ){
            return true;
        }

        $cols = $collection->getCollections()->toArray();
        if (count($cols) > 0 ){
            foreach ($cols as $col){
                return $this->checkCollection($service, $col);
            }
        }
        return false;
    }
    public function getTrueServiceFilials(Service $service, array $filials)
    {
        $true_filials = [];
        foreach ($filials as $fil){
            $servs = $this->filSerRepo->findServiceFilialReference($service, $fil);
            if (count($servs) > 0){
                $true_filials[] = $fil;
            }
        }
        return $true_filials;
    }
}
