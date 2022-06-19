<?php

namespace App\DataFixtures;

use App\Entity\Schedule;
use App\Entity\ScheduleComplect;
use App\Entity\Session;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ScheduleComplectFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {

        $this->createMany(ScheduleComplect::class, 10 , function (ScheduleComplect $complect) use ($manager) {
            $complect
                ->setSchedule($this->getRandomReference(Schedule::class))
                ->setSession($this->getRandomReference(Session::class))
            ;
        });

    }

    public function getDependencies()
    {
        return [
            SessionFixtures::class,
            ScheduleFixtures::class,
        ];
    }


}
