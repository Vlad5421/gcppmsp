<?php

namespace App\Controller\Admin;

use App\Entity\Schedule;
use App\Entity\ScheduleInterval;
use App\Entity\Service;
use App\Entity\User;
use App\Form\ScheduleFormType;
use App\Repository\FilialRepository;
use App\Repository\ScheduleRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use App\Services\ScheduleSaver;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleAdminController extends AbstractController
{
    #[
        Route('/admin/schedule/all', name: 'app_admin_schedule'),
        IsGranted('ROLE_ADMIN')
    ]
    public function adminArticles(ScheduleRepository $scheduleRepository, Request $request, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {

        $pagination = $paginator->paginate(
            $scheduleRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 15 /*limit per page*/
        );


        return $this->render('admin/schedule_admin/list_schedules.html.twig', [
            'page' => 'Список услуг',
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
        ]);
    }

    #[Route('/api/admin/schedule/create', name: 'api_admin_schedule_create', methods: "POST"), IsGranted('ROLE_ADMIN')]
    public function apiCreateSchedule(
        Request $request,
        UserRepository $userRepository,
        FilialRepository $filialRepository,
        ScheduleRepository $scheduleRepository,
        EntityManagerInterface $em,
    )
    {
        $schedule_status = "double";
        $intervals_count = 0;
        $status = 200;
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy(["id"=> intval($data["form"]["worker_id"])]);
        $filial = $filialRepository->findOneBy(["id"=> intval($data["form"]["filial_id"])]);

        if ( !$scheduleRepository->findOneBy(["filial" => $filial->getId(), "worker"=>$user->getId()]) ){
            $schedule = (new Schedule())
                ->setName($data["form"]["sch_name"])
                ->setWorker($user)
                ->setFilial($filial)
            ;
            $schedule_status = $this->saveSchedule($em, $schedule);
            $intervals_count = $this->saveIntervals($schedule, $data["schedule"], $em);
            $status = 201;
        }

//        dd(['schedule' => $schedule_status, 'count' => $intervals_count]);

        return new JsonResponse(json_encode(['schedule' => $schedule_status, 'count' => $intervals_count], true), $status);
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


    #[Route('/admin/schedule/edit/{id}', name: 'app_admin_schedule_edit')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ScheduleFormType::class, new Schedule());
        $form->handleRequest($request);

        return $this->render('admin/schedule_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать расписание',
        ]);
    }
}
