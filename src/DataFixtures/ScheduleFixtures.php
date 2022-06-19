<?php

namespace App\DataFixtures;

use App\Entity\Schedule;
use Doctrine\Persistence\ObjectManager;

class ScheduleFixtures extends BaseFixtures
{
    private array $names = ['Базовое', 'Второе'];

    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i<count($this->names); $i++) {
            $this->create(Schedule::class, function (Schedule $user) use ($manager, $i) {
                $user
                    ->setName($this->names[$i]);
            }, $i);
        }
        $manager->flush();

    }
}
