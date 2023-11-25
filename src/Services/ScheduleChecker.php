<?php

namespace App\Services;

use App\Entity\Schedule;
use App\Repository\ScheduleIntervalRepository;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleChecker
{

    private ScheduleRepository $scheduleRepository;
    private ScheduleIntervalRepository $scheduleIntervalRepository;
    private EntityManagerInterface $em;

    public function __construct
    (
     ScheduleRepository $scheduleRepository,
     ScheduleIntervalRepository $scheduleIntervalRepository,
     EntityManagerInterface $em,
    )
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleIntervalRepository = $scheduleIntervalRepository;
        $this->em = $em;
    }

    public function checkAndDellCustomDateIntervals(Schedule $schedule, $date)
    {
        $intervals = $this->checkCustomDateIntervals($schedule, $date);
        if ($intervals){
            $this->deliteCustomDateIntervals($intervals);
        }
        return true;
    }

    public function checkCustomDateIntervals(Schedule $schedule, $date): ?array
    {
        return $this->scheduleIntervalRepository->findBy(["schedule"=>$schedule, "customDate"=>$date]);
    }

    public function deliteCustomDateIntervals(array $intervals):void
    {
        foreach ($intervals as $interval) {
            $this->em->remove($interval);
            $this->em->flush();
        }

    }

}