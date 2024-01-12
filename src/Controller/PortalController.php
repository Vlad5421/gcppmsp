<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ImageGalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortalController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('home.html.twig', ['page' => 'Главная страница']);
    }

    #[Route('/manage-panel', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('admin/admin_index.html.twig', ['page' => 'Админка']);
    }
}
