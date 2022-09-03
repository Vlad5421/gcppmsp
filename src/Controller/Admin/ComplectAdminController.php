<?php

namespace App\Controller\Admin;

use App\Entity\Complect;
use App\Form\ComplectFormType;
use App\Repository\ComplectRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComplectAdminController extends AbstractController
{
    #[
        Route('/admin/complects/all', name: 'app_admin_complects'),
        IsGranted('ROLE_SERVICE_ADMIN')
    ]
    public function adminArticles(ComplectRepository $complectRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $pagination = $paginator->paginate(
            $complectRepository->findAllWithSearch(
                $request->query->get('q'),
                $request->query->has('showDeleted')
            ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 5 /*limit per page*/
        );


        return $this->render('admin/complects_admin/list_complects.html.twig', [
            'page' => 'Список комплектованых услуг',
            'collection' => $pagination,
        ]);
    }
    #[Route('/admin/complect/edit/{id}', name: 'app_admin_complect_edit')]
    public function edit(Complect $complect, Request $request, EntityManagerInterface $em, FileUploader $serviceFileUploader): Response
    {
        $form = $this->createForm(ComplectFormType::class, $complect);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $form->getData();

            $em->persist($complect);
            $em->flush();
            $this->addFlash('flash_message', 'Услуга добавлена');

            return $this->redirectToRoute('app_admin_service_create');

        }

        return $this->render('admin/filial_admin/create_comlect.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }
}