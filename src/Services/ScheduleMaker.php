<?php

namespace App\Services;

use App\Repository\CardRepository;
use App\Repository\ComplectRepository;

class ScheduleMaker
{
    private ComplectRepository $compRepository;
    private CardRepository $cardRepository;

    public function __construct(ComplectRepository $compRepository, CardRepository $cardRepository)
    {
        $this->compRepository = $compRepository;
        $this->cardRepository = $cardRepository;
    }

    function create($filial, $service, $deteReques):array
    {
        $complect = $this->compRepository->findOneBy(['filial' => $filial, 'service' => $service]);

        $schedule = $complect->getSchedule()->getSessions();
        $specs = $complect->getUsers();
        $date = $this->normaslDate($deteReques);
        $schedule_items = [];

        for ($i=0; $i< count($schedule); $i++){
            $item = [];
            $item['specs'] = [];
            $ses = $schedule[$i];
            array_push($item, $ses) ;

            foreach ($specs as $spec){
                $hus = $this->cardRepository->getTrueCards($spec->getId(), $date, $ses->getId());
                if(! $hus){ array_push($item['specs'], $spec) ;}
            }
            array_push($schedule_items, $item);
        }

        return $schedule_items;
    }

    private function normaslDate($date): string
    {
        $date_array = explode('.', $date);
        $date_array[2] = "20".$date_array[2];
        return implode('-', array_reverse($date_array));
    }
}
