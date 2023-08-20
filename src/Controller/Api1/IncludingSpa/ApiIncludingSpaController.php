<?php

namespace App\Controller\Api1\IncludingSpa;

use App\Entity\Card;
use App\Entity\Collections;
use App\Entity\Filial;
use App\Entity\FilialService;
use App\Entity\User;
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
        $scheds = [];
        foreach ($data["schedules"] as $schedule){
            $sched = [];
            $sched["worker"] = $this->serializeIt([$schedule["worker"]])[0];
            $sched["intervals"] = $this->serializeIt($schedule["intervals"]);
            $scheds[] = $sched;
        }
        $data["schedules"] = $scheds;
        return new JsonResponse($data, 200) ;
    }

    #[Route('/api1/spa/get-collections', name: 'api1_spa_get-collections', methods: "GET")]
    public function getCollections(SpaMaker $spaMaker): Response
    {
//        dd(Collections::class);
        $data = $spaMaker->getCollectionsFilials();
        return $this->json($this->serializeIt($data ));
    }

    #[Route('/api1/spa/get-filials/collections/{collection_id}', name: 'api1_spa_get-filials', methods: "GET")]
    public function getFilials(SpaMaker $spaMaker, $collection_id ): Response
    {
        $data = $spaMaker->getFilialsFromCollection($collection_id);
        return $this->json($this->serializeIt($data));
    }

    #[Route('/api1/spa/get-services/filial/{filial_id}', name: 'api1_spa_get-services', methods: "GET")]
    public function getServices(SpaMaker $spaMaker, $filial_id ): Response
    {
        $data = $spaMaker->getServicesFromFilial($filial_id);
        return $this->json($this->serializeIt($data,)) ;
    }

    //////////
    // Велосипеды сереализаторы сущностей
    /////////

    protected function serializeIt(array $collection): array
    {
        $colls = [];
        foreach ($collection as $entity){
//            dd(get_class($entity));
            $colls[] = $this->getArray(get_class($entity), $entity);
        }
        return $colls;
    }

    protected function getArray(string $name, $entity): array
    {
        switch ($name) {
            case "App\Entity\Collections":
                /** @var Collections $entity */
                $arr = [
                    "id" => $entity->getId(),
                    "name" => $entity->getName(),
                ];
                break;
            case "App\Entity\FilialService":
                /** @var FilialService $entity */
                $arr= [
                    "id" => $entity->getService()->getId(),
                    "name" => $entity->getService()->getName(),
                    "duration" => $entity->getService()->getDuration(),
                    "price" => $entity->getService()->getPrice(),
                    "image" => "uploads/logos/" . $entity->getService()->getServiceLogo(),
                ];
                break;
            case "App\Entity\Filial":
                /** @var Filial $entity */
                $arr= [
                    "id" => $entity->getId(),
                    "name" => $entity->getName(),
                    "address" => $entity->getAddress(),
                    "collection" => $entity->getCollection()->getId(),
                ];
                break;
            case "Proxies\__CG__\App\Entity\User":
                /** @var User $entity */
                $arr= [
                    "id" => $entity->getId(),
                    "name" => $entity->getFIO(),
                ];
                break;
            case "App\Entity\Card":
                /** @var Card $entity */
                $arr= [
                    "start" => $entity->getStart(),
                    "end" => $entity->getEndTime(),
                ];
                break;
        }

        return $arr;
    }

}