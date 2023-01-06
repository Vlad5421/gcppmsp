<?php

namespace App\Controller\Admin;

use App\Entity\Holiday;
use App\Form\HolidayFormType;
use App\Repository\HolidayRepository;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HolidayAdminController extends AbstractController
{

    #[Route('/admin/schedule/holiday', name: 'app_admin_schedule_holiday')]
    public function holiday(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(HolidayFormType::class, new Holiday());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $holiday = $form->getData();

            $em->persist($holiday);
            $em->flush();
            $this->addFlash('flash_message', 'Отпуск добавлен');

            return $this->redirectToRoute('app_admin_schedule_holiday_all');

        }
        return $this->render('admin/schedule_admin/holiday_create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать отпуск',
        ]);
    }
    #[Route('/admin/schedule/holiday/all', name: 'app_admin_schedule_holiday_all'), IsGranted('ROLE_ADMIN')]
    public function adminArticles(HolidayRepository $holidayRepository, Request $request, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {
        $pagination = $paginator->paginate(
            $holidayRepository->findAllWithActual(
                $request->query->has('showUnActual')
            ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 15 /*limit per page*/
        );

        return $this->render('admin/schedule_admin/list_holidays.html.twig', [
            'page' => 'Список отпусков',
            'collection' => $pagination,
        ]);
    }
}
