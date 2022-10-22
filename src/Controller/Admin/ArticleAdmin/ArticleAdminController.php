<?php

namespace App\Controller\Admin\ArticleAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    #[Route('/admin/article', name: 'app_admin_article')]
    public function index(): Response
    {
        return $this->render('admin/article_admin/index.html.twig', [
            'controller_name' => 'ArticleAdminController',
            "page" => "Создание записи",
        ]);
    }
}
