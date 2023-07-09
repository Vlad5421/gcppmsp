<?php

namespace App\Controller\Api1\IncludingSpa;

use App\Entity\FilialService;
use App\Services\UniversalGetData\SpaMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiIncludingSpaController extends AbstractController
{
    #[Route('/api1/spa/get-calendar/filials/{filial_id}/services/{service_id}', name: 'api1_spa_get-calendar', methods: "GET")]
    public function getCalendar(SpaMaker $spaMaker, Request $request, $filial_id, $service_id ): Response
    {
        $data = $spaMaker->getCalendarData($request, $filial_id, $service_id);
        return new JsonResponse($data, 200) ;
    }

    #[Route('/api1/spa/get-filials/collections/{collection_id}', name: 'api1_spa_get-filials', methods: "GET")]
    public function getFilials(SpaMaker $spaMaker, $collection_id ): Response
    {
        $data = $spaMaker->getFilialsFromCollection($collection_id);
        return $this->json($this->filCollectToArray($data)) ;
    }

    #[Route('/api1/spa/get-services/filial/{filial_id}', name: 'api1_spa_get-services', methods: "GET")]
    public function getServices(SpaMaker $spaMaker, $filial_id ): Response
    {
        $data = $spaMaker->getServicesFromFilial($filial_id);
        return $this->json($this->filsServsCollectToArray($data)) ;
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

    private function filsServsCollectToArray(array $collection): array
    {
        /** @var FilialService $complect */
        $servs= [];
        foreach ($collection as $complect){
            $servs[] = [
                "id" => $complect->getService()->getId(),
                "name" => $complect->getService()->getName(),
                "duration" => $complect->getService()->getDuration(),
                "price" => $complect->getService()->getPrice(),
            ];
        }
        return $servs;
    }
}