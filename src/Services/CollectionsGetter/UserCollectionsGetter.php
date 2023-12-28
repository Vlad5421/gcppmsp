<?php

namespace App\Services\CollectionsGetter;

use App\Entity\User;
use App\Repository\ServiceRepository;
use App\Repository\UserServiceRepository;

class UserCollectionsGetter
{
    private UserServiceRepository $usr;
    private ServiceRepository $ser_repo;

    public function __construct(UserServiceRepository $usr, ServiceRepository $ser_repo)
    {
        $this->usr = $usr;
        $this->ser_repo = $ser_repo;
    }

    public function getServices(User $user): array
    {

        $us =  $this->usr->findBy(
            ["worker" => $user]
        );
        $services = [];
        $j = 0;
        for ($i = 0; $i < count($us); $i++){
//            dump($this->ser_repo->findWithId( intval($us[$i]->getService()->getId())));
//            dump(!count($this->ser_repo->findWithId( intval($us[$i]->getService()->getId())) ) == 0 );
            if (!count($this->ser_repo->findWithId( intval($us[$i]->getService()->getId())) ) == 0 ){
                $services[$j] = $us[$i]->getService();
                $j += 1;
            }
        }
        return $services;
    }

}