<?php

namespace App\Controller;

use App\Services\ListMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArmController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ListMaker $listMaker): Response
    {
        return $this->render('home.html.twig', ['page'=>'Главная страница']);
    }
}
