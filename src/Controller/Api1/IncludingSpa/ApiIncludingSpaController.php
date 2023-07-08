<?php

namespace App\Controller\Api1\IncludingSpa;

use App\Entity\Filial;
use App\Services\UniversalGetData\SpaMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiIncludingSpaController extends AbstractController
{
    #[Route('/api1/spa/get-calendar/filials/{filial_id}/services/{service_id}', name: 'api1_spa_get-calendar', methods: "get")]
    public function getCalendar(SpaMaker $spaMaker, Request $request, $filial_id, $service_id ): Response
    {
        $data = $spaMaker->getCalendarData($request, $filial_id, $service_id);
        return new JsonResponse($data, 200) ;
    }

    #[Route('/api1/spa/get-filials/collections/{collection_id}', name: 'api1_spa_get-filials', methods: "get")]
    public function getFilials(SpaMaker $spaMaker, $collection_id ): Response
    {
        $data = $spaMaker->getFilialsFromCollection($collection_id);
        return $this->json($this->filCollectToArray($data)) ;
    }


    //////////
    // Велосипеды сереализаторы сущностей
    /////////

    private function filCollectToArray(array $filials): array
    {
        $fils = [];
        foreach ($filials as $filial){
            $fils[] = [
                "id" => $filial->getId(),
                "name" => $filial->getName(),
                "address" => $filial->getAddress(),
                "collection" => $filial->getCollection()->getId(),
            ];
        }
        return $fils;
    }
}