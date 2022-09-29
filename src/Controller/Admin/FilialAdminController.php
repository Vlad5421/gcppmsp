<?php

namespace App\Controller\Admin;

use App\Entity\Complect;
use App\Entity\Filial;
use App\Entity\FilialService;
use App\Form\ComplectFormType;
use App\Form\FilialFormType;
use App\Form\FilialServiceFormType;
use App\Repository\FilialServiceRepository;
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
            $this->addFlash('flash_message', 'Филиал создан');

            return $this->redirectToRoute('app_admin_filial_create');

        }

        return $this->render('admin/filial_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }
    #[Route('/admin/filial-service/create', name: 'app_admin_filialservice_create')]
    public function createComplect( Request $request, EntityManagerInterface $em, FilialServiceRepository $fsRepo): Response
    {
        $form = $this->createForm(FilialServiceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            /** @var FilialService $complect */
            $complect = $form->getData();

            $check = $fsRepo->findOneBy(['filial' => $complect->getFilial(), "service" => $complect->getService()]);

            if ($check){
                $this->addFlash('flash_message', "!ВНИМАНИЕ. Этому филиалу уже назначена эта услуга");
            } else {
                $em->persist($complect);
                $em->flush();
                $this->addFlash('flash_message', 'Услуга добавлена к филиалу');
            }

            return $this->redirectToRoute('app_admin_filialservice_create');

        }

        return $this->render('admin/filial_admin/create_comlect.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }
}
