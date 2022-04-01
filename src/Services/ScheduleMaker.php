<?php

namespace App\Services;

use App\Repository\ComplectRepository;

class ScheduleMaker
{
    private ComplectRepository $compRepository;

    public function __construct(ComplectRepository $compRepository)
    {
        $this->compRepository = $compRepository;
    }

    function create($filial, $service)
    {
        $complect = $this->compRepository->findOneBy(['filial' => '1', 'service' => '1']);

        $schedule = $complect->getSchedule()->getSessions();
        return $schedule;
    }
}
