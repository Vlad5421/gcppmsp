<?php

namespace App\Controller\Api1\Crm;

use App\Entity\Card;
use App\Repository\CardRepository;
use App\Repository\ComplectRepository;
use App\Repository\FilialRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Services\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiBoockingController extends AbstractController
{
    // Создание записи по апи - работает
    #[Route('/api1/crm/boocking/createcard', name: 'app_api1_crm_boocking_createcard', methods: "POST")]
    public function boockingCreate
    (Request $request,
     CardRepository $cardRepo,
     EntityManagerInterface $em,
     UserRepository $specRepo,
     FilialRepository $filRepo,
     ServiceRepository $serRepo,
    ): Response
    {
        $data = $request->query;

        $filial = $filRepo->findOneBy(['id' => $data->get("filial")]);
        $service = $serRepo->findOneBy(['id'=> $data->get("service")]);
        $specialist = $specRepo->findOneBy(['id'=>$data->get("spec")]);
        $date = new \DateTime($this->normalsDate($data->get("date")));
        $cardCollection = $cardRepo->findBy(["filial"=>$data->get("filial"), "specialist"=>$data->get("spec"), "date"=>$date]);


        $newCard = new Card();
        $newCard
            ->setFilial($filial)
            ->setService($service)
            ->setSpecialist($specialist)
            ->setStart((integer)$data->get("time"))
            ->setEndTime((integer)$data->get("time")+45)
            ->setDate($date)
        ;
//        dd($newCard);
        if(!$this->checkCard($cardCollection, $newCard)){
            $em->persist($newCard);
            $em->flush();

            return new JsonResponse(["id" => $newCard->getId()], 201) ;
        };



        return new JsonResponse(["no" => "уже занято"], 200) ;
    }

    #[Route('/api1/crm/services/delite', name: 'app_api1_crm_service_delite'), IsGranted('ROLE_SERVICE_ADMIN')]
    public function serviceDelite(ComplectRepository $repo, EntityManagerInterface $em, Request $request)
    {
        $data = json_decode($request->getContent());

        $complect = $repo->findOneBy(['id'=> $data->del_id]);
        $complect->setDeletedAt(new \DateTime());
        $em->persist($complect);
        $em->flush();

//        try {
//            $repo->remove($service, true);
//        } catch (\Exception $exept) {
//            return new JsonResponse(["exeption" => $exept, 'result' => 'NODELITED'], 304);
////            $this->redirectToRoute('app_admin_service_create');
//        }


        return new JsonResponse(["service" => $complect->getId(), 'result' => 'DELITED'], 200);
    }

    ///////
    // Служебные методы
    ////
    public function normalsDate($date): string
    {
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
