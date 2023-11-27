<?php

namespace App\CollectionsGetter;

use App\Entity\Filial;
use App\Repository\FilialServiceRepository;

class FilialCollectionsGetter
{
    private FilialServiceRepository $services;

    public function __construct(FilialServiceRepository $services)
    {
        $this->services = $services;
    }

    public function getServices(Filial $filial): array
    {

        $us =  $this->services->findBy(
            ["filial" => $filial]
        );

        for ($i = 0; $i < count($us); $i++){
            $us[$i] = $us[$i]->getService();
        }
        return $us;
    }
}