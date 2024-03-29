<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ComplectFormType;
use App\Form\ServiceFormType;
use App\Repository\ServiceRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceAdminController extends AbstractController
{
    #[
        Route('/manage-panel/service/all', name: 'app_admin_services'),
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
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 5 /*limit per page*/
        );


        return $this->render('admin/service_admin/list_services.html.twig', [
            'page' => 'Список услуг',
            'collection' => $pagination,
        ]);
    }
    #[Route('/manage-panel/service/create', name: 'app_admin_service_create')]
    public function create(Request $request, EntityManagerInterface $em, FileUploader $serviceFileUploader): Response
    {
        $form = $this->createForm(ServiceFormType::class, new Service());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $service = $this->handleFormRequest($serviceFileUploader, $form);

            $em->persist($service);
            $em->flush();
            $this->addFlash('flash_message', 'Услуга добавлена');

            return $this->redirectToRoute('app_admin_services');

        }

        return $this->render('admin/service_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }

    #[Route('/manage-panel/service/edit/{id}', name: 'app_admin_service_edit')]
    public function edit(Service $service, Request $request, EntityManagerInterface $em, FileUploader $serviceFileUploader): Response
    {
        $form = $this->createForm(ServiceFormType::class, $service);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $service = $this->handleFormRequest($serviceFileUploader, $form);

            $em->persist($service);
            $em->flush();
            $this->addFlash('flash_message', 'Услуга добавлена');

            return $this->redirectToRoute('app_admin_service_create');

        }

        return $this->render('admin/service_admin/create.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
            'page' => 'Редактировать услугу',
        ]);
    }

    #[Route('/manage-panel/service/delite/{id}', name: 'app_admin_service_delite')]
    public function delite(Service $service, EntityManagerInterface $em): Response
    {
//        $time = new \DateTime("now");
//        $service->setDeletedAt($time);
//        $em->persist($service);
        $em->remove($service);
        $em->flush();
        $this->addFlash('flash_message', 'Услуга удалена');

        return $this->redirectToRoute("app_admin_services");
    }

    public function handleFormRequest(FileUploader $serviceFileUploader, $form)
    {
        /** @var Service $service */
        $service = $form->getData();

        /** @var UploadedFile|null $image */
        $image = $form->get('image')->getData();

        if ($image) {
            $fileName = $serviceFileUploader->uploadFile($image, $service->getServiceLogo());
            $service->setServiceLogo($fileName);
        }
        return $service;

    }
}
