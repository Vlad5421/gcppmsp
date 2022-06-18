<?php

namespace App\Controller\Admin;

use App\Entity\Complect;
use App\Entity\Filial;
use App\Form\ComplectFormType;
use App\Form\FilialFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilialAdminController extends AbstractController
{
    #[Route('/admin/filial/create', name: 'app_admin_filial_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FilialFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            /** @var Filial $filial */
            $filial = $form->getData();
//            $filial->setServiceLogo('/img/priem-psy.jpg');
            
            $em->persist($filial);
            $em->flush();

            return $this->redirectToRoute('app_admin_filial_create');

        }

        return $this->render('admin/filial_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }
    #[Route('/admin/complect/create', name: 'app_admin_complect_create')]
    public function createComplect( Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ComplectFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            /** @var Complect $complect */
            $complect = $form->getData();
//            $filial->setServiceLogo('/img/priem-psy.jpg');

            $em->persist($complect);
            $em->flush();

            return $this->redirectToRoute('app_admin_complect_create');

        }

        return $this->render('admin/filial_admin/create_comlect.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }
}
