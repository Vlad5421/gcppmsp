<?php

namespace App\Controller\Api1\IncludingSpa;

use App\Entity\Card;
use App\Repository\CardRepository;
use App\Repository\FilialRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Services\CardSaver\CardSaver;
use App\Services\CustomSerializer;
use App\Services\UniversalGetData\SpaMaker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiIncludingSpaController extends AbstractController
{
    #[Route('/api1/spa/get-calendar/filials/{filial_id}/services/{service_id}', name: 'api1_spa_get-calendar', methods: "GET")]
    public function getCalendar(SpaMaker $spaMaker, Request $request, $filial_id, $service_id) : Response
    {
        $data = $spaMaker->getCalendarData($request, $filial_id, $service_id);

        if (! count($data) > 0)
        {
            return new JsonResponse([], 200);
        }

        $scheds = $spaMaker->getFilialServiceSchedule($data);

        return new JsonResponse($scheds, 200);
    }

    #[Route('/api1/spa/get-collections', name: 'api1_spa_get-collections', methods: "GET")]
    public function getCollections(SpaMaker $spaMaker) : Response
    {
        //        dd(Collections::class);
        //        $data = $spaMaker->getCollectionsFilials();
        return $this->json($spaMaker->getCollectionsFilials());
    }

    #[Route('/api1/spa/get-filials/collections/{collection_id}', name: 'api1_spa_get-filials', methods: "GET")]
    public function getFilials(Request $request, CustomSerializer $serializer, SpaMaker $spaMaker, $collection_id) : Response
    {
        $data = $spaMaker->getFilialsFromCollectionAndService($collection_id, $request->query->get("service"));
        return $this->json($serializer->serializeIt($data));
    }

    #[Route('/api1/spa/get-filials/service/{collection_id}', name: 'api1_spa_get-filials-from-service', methods: "GET")]
    public function getFilialsFromService(CustomSerializer $serializer, SpaMaker $spaMaker, $collection_id) : Response
    {
        $data = $spaMaker->getFilialsFromService($collection_id);
        //        dd($data);
        return $this->json($serializer->serializeIt($data));
    }

    #[Route('/api1/spa/get-services/filial/{filial_id}', name: 'api1_spa_get-services', methods: "GET")]
    public function getServices(CustomSerializer $serializer, SpaMaker $spaMaker, $filial_id) : Response
    {
        $data = $spaMaker->getServices($filial_id);
        return $this->json($serializer->serializeIt($data));
    }
    #[Route('/api1/spa/get-services/all', name: 'api1_spa_get-services-all', methods: "GET")]
    public function getServicesAll(CustomSerializer $serializer, SpaMaker $spaMaker) : Response
    {
        $data = $serializer->serializeIt($spaMaker->getServices());
        $cache_data = [];
        $out_data = [];
        if (count($data) > 0)
        {
            $order = $this->getParameter("service_order");
            foreach ($data as $service)
            {
                $index = array_search($service["id"], $order);
                $cache_data[$index] = $service;
            }
            for ($i = 0; $i < count($data); $i++)
            {
                $out_data[$i] = $cache_data[$i];
            }
        }
        return $this->json($out_data);
    }

    #[Route('/api1/spa/createvisitor', name: 'api1_spa_createvisitor', methods: "POST")]
    public function createVisitor(
        Request $request,
        CardSaver $card_saver,

    ) : Response {
        $form_data = $request->request->all();
        foreach ($form_data as $key => $value)
        {
            $form_data[$key] = addslashes($value);
        }

        $saver = $card_saver->createVistor($form_data);

        $responses = [
            "created" => [
                "object" => ["created" => "true"],
                "code" => 201
            ],
            "non_empty" => [
                "object" => ["error" => "visitor with this card non empty"],
                "code" => 200
            ],
            "non_card" => [
                "object" => ["error" => "card no found"],
                "code" => 200
            ],
        ];

        return new JsonResponse($responses[$saver]["object"], $responses[$saver]["code"]);
    }

    // Создание записи по апи для SPA - работает
    #[Route('/api1/spa/createcard', name: 'api1_spa_create-card', methods: "POST")]
    public function createCard(
        Request $request,
        CardRepository $cardRepo,
        EntityManagerInterface $em,
        UserRepository $specRepo,
        FilialRepository $filRepo,
        ServiceRepository $serRepo,
    ) : Response {
        $form_data = $request->request->all();
        //        dd($form_data);

        $filial = $filRepo->findOneBy(['id' => $form_data["filial"]]);
        $service = $serRepo->findOneBy(['id' => $form_data["service"]]);
        $specialist = $specRepo->findOneBy(['id' => $form_data["spec"]]);
        $date = new \DateTime($this->normalsDate($form_data["date"]));
        $cardCollection = $cardRepo->findBy(["filial" => $form_data["filial"], "specialist" => $form_data["spec"], "date" => $date]);


        $newCard = (new Card())
            ->setFilial($filial)
            ->setService($service)
            ->setSpecialist($specialist)
            ->setStart((int) $form_data["time"])
            ->setEndTime((int) $form_data["time"] + intval($service->getDuration()))
            ->setDate($date);
        //        dd($newCard);
        if (! $this->checkCard($cardCollection, $newCard))
        {
            $em->persist($newCard);
            $em->flush();

            return new JsonResponse(["id" => $newCard->getId()], 201);
        }
        ;



        return new JsonResponse(["error" => "Время занято"], 200);
    }

    ///////
    // Служебные методы
    ////
    public function normalsDate($date) : string
    {
        $date = trim($date, "'\" \n\r\t\v\x00");
        $date_array = explode('.', $date);
        return implode('-', array_reverse($date_array));
    }

    public function checkCard($cardCollection, $newCard)
    {
        $i = 0;
        $chekedCards = [];
        foreach ($cardCollection as $card)
        {
            /**
             * @var Card $newCard
             * @var Card $card
             */
            if ($newCard->getEndTime() <= $card->getStart() || $newCard->getStart() >= $card->getEndTime())
            {
                $i++;
            } else
            {

                $chekedCards[] = $card;
            }
        }
        return count($chekedCards) > 0;
    }
}
