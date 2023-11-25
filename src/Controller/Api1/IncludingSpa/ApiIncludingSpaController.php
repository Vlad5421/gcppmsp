<?php

namespace App\Controller\Api1\IncludingSpa;

use App\Entity\Card;
use App\Entity\Visitor;
use App\Repository\CardRepository;
use App\Repository\FilialRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\VisitorRepository;
use App\Services\CustomSerializer;
use App\Services\MailService;
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
//            dd($schedule["intervals"]);
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
    public function createVisitor(Request $request,
                                  VisitorRepository $visitorRepository,
                                  EntityManagerInterface $em,
                                  CardRepository $cardRepository,
                                  MailService $mailer
    ): Response
    {
        $form_data = $request->request->all();

        $card = $cardRepository->find((integer)$form_data["card_id"]);
        if ($card){
//            dd($card);
            $filial = $card->getFilial();
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

                $man_time = str_pad(intdiv($card->getStart(), 60), 2, "0", STR_PAD_LEFT). ":" .str_pad($card->getStart()%60, 2, "0", STR_PAD_LEFT);
                $fromEmail = 'vladislav_ts@list.ru';
                $fromName = 'Психологический центр';
                $date = $card->getDate()->format("d.m.Y");
                $service_name = $card->getService()->getName();
                $many = $card->getService()->getPrice();
                $address = $filial->getAddress();
                $FIO_worker = $card->getSpecialist()->getFIO();
                $toEmail = $form_data["email"];
                $visitor_name = $form_data["fullname"];
                $reason = $form_data["reason"];
                $textMail =
"Добрый день, консультация назначена!
ФИО: $visitor_name,
Когда: $date - $man_time (ОМСК),
Услуга: $service_name,
Специалист: $FIO_worker,
Стоимость: $many,
Адрес: $address
Телефн: +7 (3812) 77-77-79.

"
                ;
                $visitor_phone = $form_data["phone"];
                $consult_form = $form_data["formConsultation"];
                $mailer->sendMail($fromEmail, $fromName, $toEmail, $textMail);
                $textMail = $textMail . "
Указанный телефон для связи: $visitor_phone,
Тип консультации: $consult_form
Цель (причина): $reason
"
                ;

                $toEmail = $card->getSpecialist()->getEmail();
                $mailer->sendMail($fromEmail, $fromName, $toEmail, $textMail);




                return new JsonResponse(["created" => "true"], 201) ;
            } else {
                return new JsonResponse(["error" => "visitor with this card non empty"], 200) ;
            }
        }

        return new JsonResponse(["error" => "card no found"], 200) ;
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
        $form_data = $request->request->all();
//        dd($form_data);

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
            ->setEndTime((integer)$form_data["time"]+intval($service->getDuration()))
            ->setDate($date)
        ;
//        dd($newCard);
        if(!$this->checkCard($cardCollection, $newCard)){
            $em->persist($newCard);
            $em->flush();

            return new JsonResponse(["id" => $newCard->getId()], 201) ;
        };



        return new JsonResponse(["error" => "Время занято"], 200) ;
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
        $i = 0;
        $chekedCards = [];
        foreach($cardCollection as $card){
            /**
             * @var Card $newCard
             * @var Card $card
             */
            if (  $newCard->getEndTime() <= $card->getStart() || $newCard->getStart() >= $card->getEndTime()   ) {
                $i++;
            }else{

                $chekedCards[] = $card;
            }
        }
        return count($chekedCards) > 0;
    }


}