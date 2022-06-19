<?php

namespace App\DataFixtures;

use App\Entity\Complect;
use App\Entity\Filial;
use App\Entity\Schedule;
use App\Entity\Service;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ComplectFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {

        $this->createMany(Complect::class, 10 , function (Complect $complect) use ($manager) {
            $complect
                ->setFilial($this->getRandomReference(Filial::class))
                ->setService($this->getRandomReference(Service::class))
                ->setSchedule($this->getRandomReference(Schedule::class))
            ;
        });

    }

    public function getDependencies()
    {
        return [
            FilialFixtures::class,
            ServiceFixtures::class,
            ScheduleFixtures::class,
        ];
    }


}
