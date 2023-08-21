<?php

namespace App\Controller\Api1\IncludingSpa;

use App\Entity\Card;
use App\Entity\Collections;
use App\Entity\Filial;
use App\Entity\FilialService;
use App\Entity\User;
use App\Services\CustomSerializer;
use App\Services\UniversalGetData\SpaMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiIncludingSpaController extends AbstractController
{
    #[Route('/api1/spa/get-calendar/filials/{filial_id}/services/{service_id}', name: 'api1_spa_get-calendar', methods: "GET")]
    public function getCalendar(CustomSerializer $serializer, SpaMaker $spaMaker, Request $request, $filial_id, $service_id ): Response
    {
        $data = $spaMaker->getCalendarData($request, $filial_id, $service_id);
        $scheds = [];
        foreach ($data["schedules"] as $schedule){
            $sched = [];
            $sched["worker"] = $serializer->serializeIt([$schedule["worker"]])[0];
            $sched["intervals"] = $serializer->serializeIt($schedule["intervals"]);
            $scheds[] = $sched;
        }
        $data["schedules"] = $scheds;
        return new JsonResponse($scheds, 200) ;
    }

    #[Route('/api1/spa/get-collections', name: 'api1_spa_get-collections', methods: "GET")]
    public function getCollections(CustomSerializer $serializer, SpaMaker $spaMaker): Response
    {
//        dd(Collections::class);
//        $data = $spaMaker->getCollectionsFilials();
        return $this->json($serializer->serializeIt($spaMaker->getCollectionsFilials() ));
    }

    #[Route('/api1/spa/get-filials/collections/{collection_id}', name: 'api1_spa_get-filials', methods: "GET")]
    public function getFilials(CustomSerializer $serializer, SpaMaker $spaMaker, $collection_id ): Response
    {
        $data = $spaMaker->getFilialsFromCollection($collection_id);
        return $this->json($serializer->serializeIt($data));
    }

    #[Route('/api1/spa/get-services/filial/{filial_id}', name: 'api1_spa_get-services', methods: "GET")]
    public function getServices(CustomSerializer $serializer, SpaMaker $spaMaker, $filial_id ): Response
    {
        $data = $spaMaker->getServicesFromFilial($filial_id);
        return $this->json($serializer->serializeIt($data)) ;
    }


}