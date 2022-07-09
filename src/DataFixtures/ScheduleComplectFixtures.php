<?php

namespace App\DataFixtures;

use App\Entity\ScheduleComplect;
use App\Repository\ScheduleRepository;
use App\Repository\SessionRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ScheduleComplectFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private SessionRepository $sessionRepository;
    private ScheduleRepository $scheduleRepository;

    public function __construct(SessionRepository $sessionRepository, ScheduleRepository $scheduleRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    function loadData(ObjectManager $manager)
    {
        $sched = $this->scheduleRepository->findAll();

        $sessis =$this->sessionRepository->findAll();
        $i =0;
        foreach ($sessis as $key => $ses){
            $this->create(ScheduleComplect::class, function (ScheduleComplect $complect) use ($manager, $ses, $sched) {
                $complect
                    ->setSchedule($sched[0])
                    ->setSession($ses)
                ;

            }, $i);
            $i += 1;
        }
        $manager->flush();

    }
    public function getDependencies()
    {
        return [
            SessionFixtures::class,
        ];
    }

}