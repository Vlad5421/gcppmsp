<?php

namespace App\Services;

use App\Repository\ScheduleRepository;
use App\Repository\UserServiceRepository;

class ScheduleMaker
{
    private UserServiceRepository $usRepo;
    private ScheduleRepository $scheduleRepository;

    public function __construct(UserServiceRepository $usRepo,
                                ScheduleRepository $scheduleRepository,)
    {

        $this->usRepo = $usRepo;
        $this->scheduleRepository = $scheduleRepository;
    }
    public function getScheduleCollection($filial_id, $service_id, $day_week,): array
    {
        $userServiceCollection = $this->usRepo->findBy(["service" => $service_id]);
        $scheduleCollection = [];
//        dd($userServiceCollection);
        foreach ($userServiceCollection as $us){
            $scheduleCollection[] = $this->scheduleRepository
                ->findOneBy([
                    'filial'=>$filial_id,
                    'day'=>$day_week,
                    'worker' => $us->getWorker()
                ]);
        }
        return $scheduleCollection;
    }

}