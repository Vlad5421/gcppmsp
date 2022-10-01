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
    ];

    public function getShedules($form, $em): EntityManager
    {
        /** @var Schedule $shedule */
        $schedule = $form->getData();

        foreach ($this->days as $d => $n){
            $start = (integer)$form->get($d ."_start")->getViewData()['hour']*60 + (integer)$form->get($d ."_start")->getViewData()['minute'];
            $end = (integer)$form->get($d ."_end")->getViewData()['hour']*60 + (integer)$form->get($d ."_end")->getViewData()['minute'];
            if (!$start == 0 ){
                $schedule->setDay($this->days[$d]);
                $schedule->setStart($start);
                $schedule->setEndTime($end);

                $em->persist($schedule);
            }
        }



        return $em;
    }

}