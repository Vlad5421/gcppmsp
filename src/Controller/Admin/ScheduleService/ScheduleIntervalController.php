<?php

namespace App\Controller\Admin\ScheduleService;

use App\Entity\ScheduleInterval;
use App\Repository\ScheduleIntervalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleIntervalController extends AbstractController
{
    #[
        Route('/manage-panel/schedule/interval/delete/{id}', name: 'app_schedule-interval_delete'),
        IsGranted('ROLE_SERVICE_ADMIN')
    ]
    public function deleteFromId(ScheduleInterval $interavl, ScheduleIntervalRepository $repo, Request $request) : Response
    {
        // dd($interavl);
        $date = $interavl->getCustomDate();
        $repo->remove($interavl, true);
        $this->addFlash('flash_message', 'Удален 1 интервал из даты: ' . date_format($date, "d.m.Y"));

        return $this->redirect($request->headers->get('referer'));
        // return $this->redirectToRoute("app_admin_schedule_all");
    }
}