<?php

namespace App\Services;

use App\Entity\Schedule;
use App\Form\ScheduleFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleSaver
{
    private array $days = [
        'pn'=>'1',
        'vt'=>'2',
        'sr'=>'3',
        'cht'=>'4',
        'pt'=>'5',
        'sb'=>'6',
        'vs'=>'7',
    ];

    public function getShedules($form, $em):bool
    {
        $scheduleData = $this->getDataFromForm($form);

        foreach ($this->days as $d => $n){
            if ($form->get($d ."_start")->getViewData()){

                $start = $this->timeToNum($form->get($d ."_start")->getViewData());
                $end = $this->timeToNum($form->get($d ."_end")->getViewData());

                if (!$start == 0 ){
                    $schedule = new Schedule();
                    $schedule
                        ->setName($scheduleData["name"])
                        ->setFilial($scheduleData["filial"])
                        ->setWorker($scheduleData["worker"])
                        ->setDay($this->days[$d])
                        ->setStart($start)
                        ->setEndTime($end);
                    $em->persist($schedule);
                    $em->flush();
                }
            }

        }

        return true;
    }

    public function timeToNum($time): int
    {
        $toArr = explode(":", $time);
        return (integer)$toArr[0]*60 + (integer)$toArr[1];
    }

    public function getDataFromForm($form): array
    {
        return [
            "name" => $form->get("name")->getViewData(),
            "filial" => $form->get("filial")->getNormData(),
            "worker" => $form->get("worker")->getNormData(),
        ];

    }

}