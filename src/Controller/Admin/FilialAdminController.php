<?php

namespace App\Controller\Admin;

use App\Entity\Filial;
use App\Entity\FilialService;
use App\Form\FilialFormType;
use App\Form\FilialServiceFormType;
use App\Repository\CollectionsRepository;
use App\Repository\FilialRepository;
use App\Repository\FilialServiceRepository;
use App\Services\CustomSerializer;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilialAdminController extends AbstractController
{
    #[Route('/admin/filial/all', name: 'app_admin_filial_all')]
    public function list(Request $request, EntityManagerInterface $em, FilialRepository $filRepo, PaginatorInterface $paginator, CustomSerializer $serialiser,): Response
    {
        $filials = $filRepo->findAll();
        $fs = $serialiser->serializeIt($filials);

        $pagination = $paginator->paginate(
            $fs, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 200 /*limit per page*/
        );


        return $this->render('admin/filial_admin/list_filials.html.twig', [
            'page' => 'Список филиалов',
            'collection' => $pagination,
            'exlude_columns' => ['image', 'collection']
        ]);
    }
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

            return $this->redirectToRoute('app_admin_filial_all');

        }

        return $this->render('admin/filial_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }
    #[Route('/admin/filial/edit/{id}', name: 'app_admin_filial_edit')]
    public function edit(Filial $filial, Request $request, EntityManagerInterface $em, FileUploader $filialFileUploader): Response
    {
        $form = $this->createForm(FilialFormType::class, $filial);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $filial = $this->handleFormRequest($filialFileUploader, $form);
            $em->persist($filial);
            $em->flush();
            $this->addFlash('flash_message', 'Филиал изменён');

            return $this->redirectToRoute('app_admin_filial_all');

        }

        return $this->render('admin/filial_admin/create.html.twig', [
            'filial' => $filial,
            'form' => $form->createView(),
            'page' => 'Редактировать филиал',
        ]);
    }

    public function handleFormRequest(FileUploader $filialFileUploader, $form): Filial
    {
        /** @var Filial $filial */
        $filial = $form->getData();

        /** @var UploadedFile|null $image */
        $image = $form->get('image')->getData();

        if ($image) {
            $fileName = $filialFileUploader->uploadFile($image, $filial->getImage());
            $filial->setImage($fileName);
        } else {
            $filial->setImage("logo-dom.jpg");
        }
        return $filial;

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
