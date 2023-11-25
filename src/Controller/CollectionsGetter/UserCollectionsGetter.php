<?php

namespace App\Controller\CollectionsGetter;

use App\Entity\User;
use App\Repository\UserServiceRepository;

class UserCollectionsGetter
{
    private UserServiceRepository $usr;

    public function __construct(UserServiceRepository $usr)
    {
        $this->usr = $usr;
    }

    public function getServices(User $user): array
    {

        $us =  $this->usr->findBy(
            ["worker" => $user]
        );

        for ($i = 0; $i < count($us); $i++){
            $us[$i] = $us[$i]->getService();
        }
        return $us;
    }

}