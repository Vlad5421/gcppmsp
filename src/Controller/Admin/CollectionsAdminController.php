<?php

namespace App\Controller\Admin;

use App\Entity\Collections;
use App\Form\CollectionsFormType;
use Doctrine\ORM\EntityManagerInterface;
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

            $em->persist($collection);
            $em->flush();
            $this->addFlash('flash_message', 'Коллекция создана');

            return $this->redirectToRoute('app_admin_collection_create');

        }

        return $this->render('admin/collections_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать коллекцию данных'
        ]);
    }
}
