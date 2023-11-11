<?php

namespace App\Controller\Admin;

use App\Entity\Collections;
use App\Form\CollectionsFormType;
use App\Repository\CollectionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionsAdminController extends AbstractController
{
    #[Route('/admin/collection/create', name: 'app_admin_collection_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CollectionsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            /** @var Collections $collection */
            $collection = $form->getData();
            $collection->setType("filial");

            $em->persist($collection);
            $em->flush();
            $this->addFlash('flash_message', 'Коллекция создана. collection='.$collection->getId());

            return $this->redirectToRoute('app_admin_collection_create');

        }

        return $this->render('admin/collections_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать коллекцию данных'
        ]);
    }

    #[Route('/admin/collection/all', name: 'app_admin_collection_all')]
    public function listAll(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, CollectionsRepository $colrepo): Response
    {
        $filials = $colrepo->findAll();

        $pagination = $paginator->paginate(
            $filials, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 200 /*limit per page*/
        );


        return $this->render('admin/collections_admin/list_collections.html.twig', [
            'page' => 'Список коллекций',
            'collection' => $pagination,
        ]);
    }
}
