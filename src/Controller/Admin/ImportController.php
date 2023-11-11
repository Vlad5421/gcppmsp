<?php

namespace App\Controller\Admin;

use App\Services\Admin\ScheduleImporter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    #[
        Route('/admin/user/import', name: 'app_admin_user_import'),
        IsGranted('ROLE_SERVICE_ADMIN')
    ]
    public function import(ScheduleImporter $scheduleImporter): Response
    {
        $scheduleImporter->readCsvs("/public/uploads/gallery/workers.csv", "/public/uploads/gallery/filials.csv");

        dd($scheduleImporter->import());
    }

}