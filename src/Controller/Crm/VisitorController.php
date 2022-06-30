<?php

namespace App\Controller\Crm;

use App\Form\VisitorFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitorController extends AbstractController
{
    #[Route('/crm/visitor/{card_id}', name: 'app_crm_visitor_create')]
    public function create(EntityManagerInterface $em, Request $request ): Response
    {
        $form = $this->createForm(VisitorFormType::class);
        $form->handleRequest($request);





        return $this->render('crm/visitor/create.html.twig', [
            'controller_name' => 'VisitorController',
        ]);
    }
}
