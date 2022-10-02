<?php

namespace App\Services;

use App\Entity\Card;
use App\Entity\Schedule;
use App\Entity\Service;
use App\Repository\CardRepository;
use App\Repository\ScheduleRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserServiceRepository;

class ScheduleMaker
{
    private UserServiceRepository $usRepo;
    private ScheduleRepository $scheduleRepository;
    private ServiceRepository $serRepo;
    private CardRepository $cardRepo;

    public function __construct
    (UserServiceRepository $usRepo,
     ScheduleRepository $scheduleRepository,
     ServiceRepository $serRepo,
     CardRepository $cardRepo,
    )
    {

        $this->usRepo = $usRepo;
        $this->scheduleRepository = $scheduleRepository;
        $this->serRepo = $serRepo;
        $this->cardRepo = $cardRepo;
    }
    public function getScheduleCollections($filial_id, $service_id, $day_week, $theDate): array
    {

        $date = new \DateTime($this->normalsDate($theDate));
        $userServiceCollection = $this->usRepo->findBy(["service" => $service_id]);
        $scheduleCollection = [];
//        dd($userServiceCollection);
        foreach ($userServiceCollection as $us){
            $schedules = $this->scheduleRepository
                ->findBy([
                    'filial'=>$filial_id,
                    'day'=>$day_week,
                    'worker' => $us->getWorker()
                ]);
            if ($schedules) {
                $scheduleCollection[] = $schedules;
            }

        }

//        dd($scheduleCollection);

        $sessCollections = [];

        foreach ($scheduleCollection as $schedules){
            $sessionsOneSchedule = [];

            foreach ($schedules as $scedule){

                    $sessionsOneSchedule[] = is_array($scedule) ? $scedule : $this->makeSessionsOneSchedule($filial_id, $service_id, $date, $scedule);

            }
            $sessCollections[] = $sessionsOneSchedule;
        }
//        dd($sessCollections);
        return $sessCollections;
    }

    public function makeSessionsOneSchedule($filial_id, $service_id, $date, $sch)
    {
        // Внутри имеем одно расписание
        $worker = $sch->getWorker();
        $sessionCollection = [];
        // имеемые записи на этот день
        $cardCollection = $this->cardRepo->findBy([
            "filial"=>$filial_id,
            "specialist" =>$worker,
            "date"=>$date,
        ]);

        // Формируем набор сессий из расписания и делаем из них записи (new Card())
        // Плюс - чекаем на совпадение с набором
        /** @var Service $service */
        $service = $this->serRepo->findOneBy(['id' => $service_id]);
        $start = $sch->getStart();


        while ( $start <= $sch->getEndTime() - $service->getDuration() ){
            $end = $start+$service->getDuration();
            $card = (new Card())->setStart($start)->setEndTime($end)->setSpecialist($worker);
            if(!$this->checkCard($cardCollection, $card)){
                $sessionCollection[] = $card;
            }
            $start = $start + $service->getDuration();
        }

        return $sessionCollection;
    }

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