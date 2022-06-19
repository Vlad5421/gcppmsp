<?php

namespace App\DataFixtures;

use App\Entity\Filial;
use Doctrine\Persistence\ObjectManager;

class FilialFixtures extends BaseFixtures
{
    private array $filials = [
        ['Мира', 'пр. Мира, 165А'],
        ['Куйбышева', 'ул. Куйбышева, 27/7'],
        ['Орловского', 'ул. Орловского, 10'],
        ['СОШ №145', 'ул. Какая-нибудь линия, 140']
    ];

    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i<count($this->filials); $i++){
            $this->create(Filial::class, function (Filial $user) use ($manager,$i) {
                $user
                    ->setName($this->filials[$i][0])
                    ->setAddress($this->filials[$i][1])
                ;
            }, $i);
        }

        $manager->flush();

    }
}
