<?php

namespace App\Twig;

use App\Repository\CollectionsRepository;
use Twig\Extension\RuntimeExtensionInterface;

class AppGetEntityList implements RuntimeExtensionInterface
{

    private CollectionsRepository $collectionsRepository;

    public function __construct(CollectionsRepository $collectionsRepository)
    {
        $this->collectionsRepository = $collectionsRepository;
    }

    public function getlist()
    {
        return $this->collectionsRepository->findAll();

    }

}