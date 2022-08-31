<?php

namespace App\Services;

use App\Entity\ScheduleComplect;
use App\Repository\CardRepository;
use App\Repository\ComplectRepository;
use App\Repository\UserComplectReferenceRepository;

class ScheduleMaker
{
    private ComplectRepository $compRepository;
    private CardRepository $cardRepository;
    private UserComplectReferenceRepository $ucomrefRepository;

    public function __construct(ComplectRepository $compRepository, CardRepository $cardRepository, UserComplectReferenceRepository $ucomrefRepository)
    {
        $this->compRepository = $compRepository;
        $this->cardRepository = $cardRepository;
        $this->ucomrefRepository = $ucomrefRepository;
    }

    function create($filial, $service, $deteReques):array
    {
//        $complect = $this->compRepository->findOneBy(['id' => $filial, 'service' => $service]);
        $complect = $this->compRepository->findOneBy(['id' => $service]);
        // Получение списка сессий через шдул комплект
        $schedule = $complect->getSchedule()->getScheduleComplects();
        $sessions = [];
        foreach ($schedule as $sched){
            /**
             * @var ScheduleComplect $sched
             */
            $ses = $sched->getSession();
            array_push($sessions, $ses);
        }
        // вот до сюда
        // меняю тут
        $ucomrefs = $this->ucomrefRepository->findByComplect($complect->getId());
        $specs = [];
        foreach ($ucomrefs as $ucomref){
            $specs[] = $ucomref->getWorker();
        }
        // меняю вот до сюда

//        $specs = $complect->getUsers(); // Получение специалистов через комплект: Филиал+Услуга

        $date = $this->normalsDate($deteReques);

        $schedule_items = []; // Массив с 1 временем и списком спецов.
        /* Схема:
        $schedule_items = [
            [
                'specs' => [
                    {объект специалиста},
                    {объект специалиста},
                     ...
                ],
                {объект сессии 1}
            ],
        [
                'specs' => [
                    {объект специалиста},
                    {объект специалиста},
                     ...
                ],
                {объект сессии 2}
            ],
        ....
        ]
        */

        for ($i=0; $i< count($sessions); $i++){
            $item = [];
            $item['specs'] = [];
            $ses = $sessions[$i];
            array_push($item, $ses) ;

            foreach ($specs as $spec){
                // Запрашиваем занята ли сессия для спеца
                $hus = $this->cardRepository->getTrueCards($spec->getId(), $date, $ses->getId());
                //Если не занята (нет в спске записей) тогда добавляем спеца в массив для этого времени
                if(! $hus){ array_push($item['specs'], $spec) ;}
            }

            array_push($schedule_items, $item);
        }

        return $schedule_items;
    }

    static function normalsDate($date): string
    {
        $date_array = explode('.', $date);
        $date_array[2] = "20".$date_array[2];
        return implode('-', array_reverse($date_array));
    }
}
