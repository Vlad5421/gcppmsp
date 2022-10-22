<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceFormType;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardAdminController extends AbstractController
{
    #[
        Route('/admin/card/all', name: 'app_admin_cards'),
        IsGranted('ROLE_SERVICE_ADMIN')
    ]
    public function adminArticles(UserRepository $userRepository, CardRepository $cardRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if ($request->query->get('q'))
            $user = $userRepository->findOneByFioLike($request->query->get('q'));
        else
            $user = null;

        $pagination = $paginator->paginate(
            $cardRepository->findAllWithUser(
                $user,
                $request->query->has('showDeleted')
            ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 5 /*limit per page*/
        );


        return $this->render('admin/card_admin/list_cards.html.twig', [
            'page' => 'Список записей',
            'collection' => $pagination,
        ]);
    }
    #[Route('/admin/card/create', name: 'app_admin_service_create')]
    public function create(Request $request, EntityManagerInterface $em, FileUploader $serviceFileUploader): Response
    {
        $form = $this->createForm(ServiceFormType::class, new Service);
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

    #[Route('/admin/card/edit/{id}', name: 'app_admin_service_edit')]
    public function edit(Service $service, Request $request, EntityManagerInterface $em, FileUploader $serviceFileUploader): Response
    {
        $form = $this->createForm(ServiceFormType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $service = $this->handleFormRequest($serviceFileUploader, $form);
            $em->persist($service);
            $em->flush();
            $this->addFlash('flash_message', 'Услуга добавлена');

            return $this->redirectToRoute('app_admin_cards');

        }

        return $this->render('admin/service_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
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
