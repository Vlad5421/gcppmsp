<?php

namespace App\DataFixtures;

use App\Entity\Collections;
use Doctrine\Persistence\ObjectManager;

class CollectionsFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(\App\Entity\Collections::class, 1, function (Collections $collections) use ($manager) {
            $collections
                ->setName("programmy-fizkulturno-sportivnoi-napravlennosti-3")
                ->setType('image')
            ;
        });

    }
}
