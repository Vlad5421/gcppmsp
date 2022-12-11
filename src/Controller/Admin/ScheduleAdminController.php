<?php

namespace App\Controller\Admin;

use App\Entity\Filial;
use App\Entity\Schedule;
use App\Entity\ScheduleInterval;
use App\Entity\User;
use App\Form\ScheduleFormType;
use App\Repository\FilialRepository;
use App\Repository\ScheduleIntervalRepository;
use App\Repository\ScheduleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleAdminController extends AbstractController
{
    #[Route('/admin/schedule/all', name: 'app_admin_schedule_all'), IsGranted('ROLE_ADMIN')]
    public function adminArticles(ScheduleRepository $scheduleRepository, Request $request, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {
        $pagination = $paginator->paginate(
            $scheduleRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 15 /*limit per page*/
        );

        return $this->render('admin/schedule_admin/list_schedules.html.twig', [
            'page' => 'Список расписаний',
            'collection' => $pagination,
        ]);
    }

    #[Route('/admin/schedule/create', name: 'app_admin_schedule_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(ScheduleFormType::class, new Schedule());
        $form->handleRequest($request);

        return $this->render('admin/schedule_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать расписание',
            'activity' => 'create',
        ]);
    }

    #[Route('/api/admin/schedule/create', name: 'api_admin_schedule_create', methods: "POST"), IsGranted('ROLE_ADMIN')]
    public function apiCreateSchedule(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        FilialRepository $filialRepository,
        ScheduleRepository $scheduleRepository,
        ScheduleIntervalRepository $scheduleIntervalRepository,
    )
    {
        // Тут создаётся шаблон ответа
        $schedule_status = "double";
        $intervals_count = 0;
        $doubled = 0;
        $status = 200;
        $response_array = [
            'schedule' => &$schedule_status,
            'made_intervals' => &$intervals_count,
            'doubled' => &$doubled,
        ];

        // Далее вычисления

        $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy(["id"=> intval($data["form"]["worker_id"])]);
        $filial = $filialRepository->findOneBy(["id"=> intval($data["form"]["filial_id"])]);

        if ($data["activity"] == "edit"){
            $schedule = $scheduleRepository->findOneBy(["filial" => $filial->getId(), "worker"=>$user->getId()]);
            $id = $schedule->getId();
            foreach ($scheduleIntervalRepository->findBy(["schedule" => $id]) as $interval){
                $this->removeEntity($interval, $em);
            }
            $intervals_count = $this->saveIntervals($schedule, $data["schedule"], $em);
        } elseif ($data["activity"] == "create"){
            if ( !$this->getScheduleToCheck($data,$user,$filial,$scheduleRepository)){
                $schedule = (new Schedule())
                    ->setName($data["form"]["sch_name"])
                    ->setWorker($user)
                    ->setFilial($filial)
                ;
                $schedule_status = $this->saveSchedule($em, $schedule);
                $intervals_count = $this->saveIntervals($schedule, $data["schedule"], $em);
                $status = 201;
            } else {
                $doubled = $scheduleRepository->findOneBy(["filial" => $filial->getId(), "worker"=>$user->getId()])->getId();
            }
        } else {
            $schedule_status = "что-то пошло совсем не так...";
            $status = 500;
        }





        return new JsonResponse(json_encode($response_array, true), $status);
    }

    public function getScheduleToCheck($data,User $user,Filial $filial,ScheduleRepository $scheduleRepository): Schedule|null
    {
        return $scheduleRepository->findOneBy(["filial" => $filial->getId(), "worker"=>$user->getId()]);
    }

    public function saveSchedule($em, $schedule): string
    {
        $em->persist($schedule);
        try {
            $em->flush();
            return "created";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function saveIntervals(Schedule $schedule, array $intervals, EntityManagerInterface $em): int
    {
        $count = 0;
        for($day=1; $day<=count($intervals); $day++){
            foreach ($intervals[$day] as $key => $interval){
                $em->persist(
                    (new ScheduleInterval())
                        ->setStart($interval["start"])
                        ->setEndTime($interval["end"])
                        ->setDay($day)
                        ->setSchedule($schedule)
                );
                $em->flush();
                $count += 1;
            }
        }
        return $count;
    }

    private function removeEntity($entity, $em): ScheduleInterval | null
    {
        $em->remove($entity);
        $em->flush();
        return $em->getRepository(get_class($entity))->findOneBy(['id' => $entity->getId()]);
    }


    #[Route('/admin/schedule/edit/{id}', name: 'app_admin_schedule_edit')]
    public function edit(Schedule $schedule, Request $request, EntityManagerInterface $em, ScheduleIntervalRepository $sirepo): Response
    {
        $form = $this->createForm(ScheduleFormType::class, $schedule);
        $form->handleRequest($request);
        $ints = $sirepo->findBySchedule($schedule);
//        dd($ints);


        return $this->render('admin/schedule_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Редактировать расписание',
            'activity' => 'edit',
            'ints_of_days' => $ints,
        ]);
    }
}
