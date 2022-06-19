<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends BaseFixtures
{
    private array $services = [
        ['name' => 'Психолог', 'img' => '/img/psih.png'],
        ['name' => 'Дифектолог', 'img' => '/img/difect.png'],
        ['name' => 'Логопед', 'img' => '/img/logop.png'],
        ['name' => 'Социальный педагог', 'img' => '/img/priem-psy.jpg'],
        ['name' => 'Юрист по вопросам детей', 'img' => '/img/psih.png'],
        ['name' => 'Пара-психолог', 'img' => '/img/logop.png'],
    ];

    public function loadData(ObjectManager $manager): void
    {
        $i = 0;
        foreach ($this->services as $serv){
            $this->create(Service::class, function (Service $service) use ($serv) {
                $service
                    ->setName($serv['name'])
                    ->setDuration(45)
                    ->setPrice('Услуга бесплатная')
                    ->setServiceLogo($serv['img'])
                ;
            }, $i);
            $i += 1;
        }
        $manager->flush();
    }
}
