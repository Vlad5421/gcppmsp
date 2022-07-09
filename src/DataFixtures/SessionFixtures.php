<?php

namespace App\DataFixtures;

use App\Entity\Schedule;
use App\Entity\Session;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SessionFixtures extends BaseFixtures
{
    private $sessions = [
        ['9:00', '9:45', '45',],
        ['10:00', '10:45', '45',],
        ['11:00', '11:45', '45',],
        ['12:00', '12:45', '45',],
        ['13:00', '13:45', '45',],
        ['14:00', '14:45', '45',],
        ['15:00', '15:45', '45',],
        ['16:00', '16:45', '45',],
    ];

    function loadData(ObjectManager $manager)
    {
        $i =0;
        foreach ( $this->sessions as $session){
            $this->create(Session::class, function (Session $user) use ($manager, $session) {
                $user
                    ->setTimeStart($session[0])
                    ->setTimeEnd($session[1])
                    ->setRest($session[2])
                ;

            }, $i);
            $i += 1;
        }
        $this->create(Schedule::class, function (Schedule $schedule) use ($manager) {
            $schedule->setName('Базовое');
        });
        $manager->flush();

    }

}
