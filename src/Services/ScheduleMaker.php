<?php

namespace App\Services;

use App\Entity\Card;
use App\Entity\Service;
use App\Entity\User;
use App\Repository\CardRepository;
use App\Repository\HolidayRepository;
use App\Repository\ScheduleIntervalRepository;
use App\Repository\ScheduleRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserServiceRepository;

class ScheduleMaker
{
    private UserServiceRepository $userServiceRepo;
    private ScheduleRepository $scheduleRepository;
    private ServiceRepository $serRepo;
    private CardRepository $cardRepo;
    private ScheduleIntervalRepository $scheduleIntervalRepository;
    private HolidayRepository $holidayRepository;

    public function __construct
    (UserServiceRepository $userServiceRepo,
     ScheduleRepository $scheduleRepository,
     ServiceRepository $serRepo,
     CardRepository $cardRepo,
     ScheduleIntervalRepository $scheduleIntervalRepository,
     HolidayRepository $holidayRepository,
    )
    {
        $this->userServiceRepo = $userServiceRepo;
        $this->scheduleRepository = $scheduleRepository;
        $this->serRepo = $serRepo;
        $this->cardRepo = $cardRepo;
        $this->scheduleIntervalRepository = $scheduleIntervalRepository;
        $this->holidayRepository = $holidayRepository;
    }
    public function getScheduleCollections($filial_id, $service_id, $day_week, string $theDate): array
    {
        // В календарьмэйкере воскресенье имеет номер 0, поэтому:
        if(intval($day_week) == 0) $day_week = 7;
        $schedules = [];
        $date = new \DateTime($this->normalsDate($theDate));
        // Получает пользователей, которые оказывают выбранную услугу
        $users = $this->getUsersFromService($service_id, $date);

        // Получаем расписания этих пользователей на выбранном филиале
        foreach ($users as $user){
            $schedulesOfUser = $this->scheduleRepository
                ->findOneBy([
                    'filial'=>$filial_id,
                    'worker' => $user,
                ]);
            if ($schedulesOfUser) {
                $schedules[] = $schedulesOfUser;
            }
        }
        dump("шедулесы");
        dump($schedules);

        // Для полученных расписаний получаем интервалы работы в указанный день недели
        $intervals = [];
        foreach ($schedules as $schedule){
            // Заполняем intervals стандартными расписаниями
            $ints = $this->scheduleIntervalRepository->findBy(['schedule'=>$schedule->getId(), 'day' => intval($day_week)]);
            if (count($ints) > 0) $intervals[$schedule->getId()] = $ints;
            // Переписываем расписание на расписание по КастомДэйт (КастомДэйт более приоритетно)
            $ints_custom_date = $this->scheduleIntervalRepository->findBy(['schedule'=>$schedule->getId(), 'customDate' => $date]);
            if (count($ints_custom_date) > 0){
                $intervals[$schedule->getId()] = $ints_custom_date;
            }
        }

        dump("интервалс");
        dump($intervals);
//        dd($schedules);
        ///////////////////////////////////////

        // this is real MAGIK
        $sessionsOfSchedule = [];
        foreach ($schedules as $schedule){
//            dump(isset($intervals[$schedule->getId()]));

            if (isset($intervals[$schedule->getId()]) && count($intervals[$schedule->getId()]) > 0){
                $sessionsOfSchedule[$schedule->getId()]["worker"] = $schedule->getWorker();
                foreach ($intervals[$schedule->getId()] as $interval){
                    dump("интервал");
                    dump($interval);
                    $peremenaya = $this->makeSessionsOneInterval($schedule->getWorker(), $filial_id, $service_id, $date, $interval) ;

                    foreach ( $this->makeSessionsOneInterval($schedule->getWorker(), $filial_id, $service_id, $date, $interval) as $card ){
                        $sessionsOfSchedule[$schedule->getId()]["intervals"][] = $card;
                    }
                }
            }

        }
//        dd($sessionsOfSchedule);

        return $sessionsOfSchedule;
    }

    public function makeSessionsOneInterval(User $worker, $filial_id, $service_id, $date, $interval):array
    {
        $sessionCollection = [];
        // имеемые записи на этот день
        $cardCollection = $this->cardRepo->findBy([
//            "filial"=>$filial_id,
            "specialist" =>$worker,
            "date"=>$date,
        ]);


        // Формируем набор сессий из расписания и делаем из них записи (new Card())
        // Плюс - чекаем на совпадение с набором
        /** @var Service $service */
        $service = $this->serRepo->findOneBy(['id' => $service_id]);
        $start = $interval->getStart();
        $truOrFalse = $start <= $interval->getEndTime() - $service->getDuration() ;
        dd( $truOrFalse);

        while ( $start <= $interval->getEndTime() - $service->getDuration() ){
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

    public function getUsersFromService($service_id, $date): array
    {
        $users = [];
        $userServiceCollection = $this->userServiceRepo->findBy(["service" => $service_id]);
        foreach ($userServiceCollection as $userService){
            $user = $userService->getWorker();
            $workNow = !$this->checkUserToHoliday($user, $date);
//            dump($workNow);
            if ($workNow){
                $users[] = $user;
            }
        }
//        dd($users);
        return $users;
    }

    public function checkUserToHoliday($user, $date)
    {
        return count($this->holidayRepository->findNowUsersHoliday($user, $date)) > 0;
    }

}