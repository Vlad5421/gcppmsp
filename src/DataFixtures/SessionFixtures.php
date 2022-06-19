<?php

namespace App\DataFixtures;

use App\Entity\Session;
use Doctrine\Persistence\ObjectManager;

class SessionFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $time = \DateTime::createFromFormat('H:i', '9:00');
        for ($i = 0; $i<10; $i++){
            $this->create(Session::class, function (Session $session) use ($manager, $time) {
                $session
                    ->setRest(45)
                    ->setTimeStart($time->format('H:i'))
                    ->setTimeEnd($time->modify('+ 45 min')->format('H:i'))
                ;
//            $manager->persist(new ApiToken($user));
            }, $i);
            $time->modify('+ 15 min');
        }
        $manager->flush();


    }
}
