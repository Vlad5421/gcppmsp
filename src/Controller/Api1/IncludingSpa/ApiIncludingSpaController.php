<?php

namespace App\Controller\Api1\IncludingSpa;

use App\Entity\Card;
use App\Entity\Collections;
use App\Entity\Filial;
use App\Entity\FilialService;
use App\Entity\User;
use App\Entity\Visitor;
use App\Form\CardFormType;
use App\Repository\CardRepository;
use App\Repository\FilialRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\VisitorRepository;
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

    #[Route('/api1/spa/createvisitor', name: 'api1_spa_createvisitor', methods: "POST")]
    public function createVisitor(Request $request, VisitorRepository $visitorRepository, EntityManagerInterface $em, CardRepository $cardRepository): Response
    {
        $form_data = $request->request->getIterator()->getArrayCopy();

        $card = $cardRepository->find((integer)$form_data["card_id"]);
//        {
//            fullname: "",
//            email: "",
//            phone: "",
//            age: 18,
//            reason: "Текст из textarea с тегами br",
//            formConsultation: "",
//        }
        if ($card){
//            dd($card);
            if (!$visitorRepository->findOneByCard($card)){
                $visitor = (new Visitor())
                    ->setName($form_data["fullname"])
                    ->setEmail($form_data["email"])
                    ->setPhoneNumber($form_data["phone"])
                    ->setAgeChildren($form_data["age"])
                    ->setReason($form_data["reason"])
                    ->setConsultForm($form_data["formConsultation"])
                    ->setCard($card)
                    ->setConsent($form_data["consent"])
                ;
                $em->persist($visitor);
                $em->flush();
                return new JsonResponse(["created" => "true"], 201) ;
            } else {
                return new JsonResponse(["error" => "visitor with this card non empty"], 500) ;
            }
        }

        return new JsonResponse(["error" => "no found card"], 500) ;
    }

    // Создание записи по апи для SPA - работает
    #[Route('/api1/spa/createcard', name: 'api1_spa_create-card', methods: "POST")]
    public function createCard
    (Request $request,
     CardRepository $cardRepo,
     EntityManagerInterface $em,
     UserRepository $specRepo,
     FilialRepository $filRepo,
     ServiceRepository $serRepo,
    ): Response
    {

        dd($request->request->all());
        return new JsonResponse($request, 201) ;

        $filial = $filRepo->findOneBy(['id' => $form_data["filial"]]);
        $service = $serRepo->findOneBy(['id'=> $form_data["service"]]);
        $specialist = $specRepo->findOneBy(['id'=> $form_data["spec"]]);
        $date = new \DateTime($this->normalsDate($form_data["date"]));
        $cardCollection = $cardRepo->findBy(["filial"=>$form_data["filial"], "specialist"=>$form_data["spec"], "date"=>$date]);


        $newCard = (new Card())
            ->setFilial($filial)
            ->setService($service)
            ->setSpecialist($specialist)
            ->setStart((integer)$form_data["time"])
            ->setEndTime((integer)$form_data["time"]+45)
            ->setDate($date)
        ;
//        dd($newCard);
        if(!$this->checkCard($cardCollection, $newCard)){
            $em->persist($newCard);
            $em->flush();

            return new JsonResponse(["id" => $newCard->getId()], 201) ;
        };



        return new JsonResponse(["error" => "Время занято"], 500) ;
    }

    ///////
    // Служебные методы
    ////
    public function normalsDate($date): string
    {
        $date = trim($date, "'\" \n\r\t\v\x00");
        $date_array = explode('.', $date);
        return implode('-', array_reverse($date_array));
    }

    public function checkCard($cardCollection, $newCard){

        $chekedCards = [];
        foreach($cardCollection as $card){
            /**
             * @var Card $newCard
             * @var Card $card
             */
            if (! ($newCard->getEndTime() <= $card->getStart() || $newCard->getStart() >= $card->getEndTime())) {
                $chekedCards[] = $card;
            }
        }
        return count($chekedCards) > 0;
    }


}