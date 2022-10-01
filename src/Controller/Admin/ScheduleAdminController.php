<?php

namespace App\Controller\Admin;

use App\Entity\Schedule;
use App\Form\ScheduleFormType;
use App\Repository\ServiceRepository;
use App\Services\ScheduleSaver;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleAdminController extends AbstractController
{
    #[
        Route('/admin/schedule/all', name: 'app_admin_schedule'),
        IsGranted('ROLE_SERVICE_ADMIN')
    ]
    public function adminArticles(ServiceRepository $serviceRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $pagination = $paginator->paginate(
            $serviceRepository->findAllWithSearch(
                $request->query->get('q'),
                $request->query->has('showDeleted')
            ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 15 /*limit per page*/
        );


        return $this->render('admin/service_admin/list_services.html.twig', [
            'page' => 'Список услуг',
            'collection' => $pagination,
        ]);
    }
    #[Route('/admin/schedule/create', name: 'app_admin_schedule_create')]
    public function create(Request $request, EntityManagerInterface $em, ScheduleSaver $schSaver): Response
    {
        $form = $this->createForm(ScheduleFormType::class, new Schedule());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

//            dd($form->get('pn_start')->getViewData());
//            dd($form);
            $em = $schSaver->getShedules($form, $em);

//            $schedule = $form->getData();
//
//            $em->persist($schedule);
            $em->flush();
            $this->addFlash('flash_message', 'Расписание добавлено');

            return $this->redirectToRoute('app_admin_schedule_create');

        }

        return $this->render('admin/schedule_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать расписание'
        ]);
    }

    #[Route('/admin/schedule/edit/{id}', name: 'app_admin_schedule_edit')]
    public function edit(Schedule $schedule, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ScheduleFormType::class, $schedule);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $schedule = $form->getData();

            $em->persist($schedule);
            $em->flush();
            $this->addFlash('flash_message', 'Расписание изменено');

            return $this->redirectToRoute('app_admin_schedule_edit');

        }

        return $this->render('admin/service_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Редактировать услугу'
        ]);
    }
}
