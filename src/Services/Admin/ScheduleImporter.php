<?php

namespace App\Services\Admin;

use App\Entity\Collections;
use App\Entity\Filial;
use App\Entity\Schedule;
use App\Entity\ScheduleInterval;
use App\Entity\User;
use App\Repository\CollectionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ScheduleImporter
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passHasher;
    private CsvReader $csvReader;
    private array $users;
    private array $filials;
    private Collections $collection;
    private array $created_filials;

    public function __construct(EntityManagerInterface      $em,
                                UserPasswordHasherInterface $passHasher,
                                CsvReader                   $csvReader,
                                CollectionsRepository       $colRepo,
    )
    {
        $this->em = $em;
        $this->passHasher = $passHasher;
        $this->csvReader = $csvReader;
        $this->collection = $colRepo->findOneBy(['id'=>1]);
    }

    public function readCsvs(string $usrs_csv, string $filials_csv): void
    {
//        $this->csvReader->setStrings($usrs_csv);
        $this->users = $this->csvReader->setStrings($usrs_csv)->getUsers();
        $this->filials = $this->csvReader->setStrings($filials_csv)->getFilials();
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @return array
     */
    public function getFilials(): array
    {
        return $this->filials;
    }

    /**
     * @return array
     */
    public function getCreatedFilials(): array
    {
        return $this->created_filials;
    }


    public function import()
    {
        set_time_limit(300);
        foreach ($this->users as $user_arr) {
            $filial = $this->createFilial($user_arr["filial"], $this->filials[(string)$user_arr["filial"]]);
            $user = $this->createUser($user_arr["FIO"]);
            $schedule = $this->createSchedule($user, $filial);
            $this->createScheduleIntervals($user_arr, $schedule);
            $this->em->flush();
        }
        return $this->getCreatedFilials();
    }

    private function createFilial(int $num, array $arr): Filial
    {
        if (isset($this->created_filials[$num])){
            return $this->created_filials[$num];
        }
        $filial = new Filial();
        $filial
            ->setName($arr[0])
            ->setAddress($arr[1])
            ->setCollection($this->collection);

        $this->em->persist($filial);
        $this->created_filials[$num] = $filial;
        return $filial;
    }
    private function createUser(string $fio): User
    {
        $user = new User();
        $user
            ->setFIO($fio)
            ->setEmail("vladislav_ts@bk.ru")
            ->setPassword($this->passHasher->hashPassword($user, '123456'));

        $this->em->persist($user);
        return $user;
    }
    private function createSchedule(User $user, Filial $filial): Schedule
    {
        $schedule = new Schedule();
        $schedule
            ->setName($user->getF_I_O() . "-" . $filial->getName())
            ->setFilial($filial)
            ->setWorker($user)
        ;

        $this->em->persist($schedule);
        return $schedule;
    }

    private function createScheduleIntervals(array $user_arr, Schedule $schedule):void
    {
        unset($user_arr["FIO"]);
        unset($user_arr["filial"]);
        for ($day = 1; $day < 8; $day++){
            if (isset($user_arr[(string)$day])){
                foreach ($user_arr[(string)$day] as $interval){
                    $ints = explode("-", $interval);
                    $newInterval = (new ScheduleInterval())
                        ->setStart($ints[0])
                        ->setEndTime($ints[1])
                        ->setDay($day)
                        ->setSchedule($schedule);
                    $this->em->persist($newInterval);
                }
            }
        }

    }


}