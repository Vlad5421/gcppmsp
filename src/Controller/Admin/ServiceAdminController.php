<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceAdminController extends AbstractController
{
    #[Route('/admin/service/create', name: 'app_admin_service_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ServiceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            /** @var Service $service */
            $service = $form->getData();
            $service->setServiceLogo('/img/priem-psy.jpg');
            //dd($service);
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('app_crm_boocking_sevices', ['filials' => 'all']);

        }

        return $this->render('admin/service_admin/index.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }
}
