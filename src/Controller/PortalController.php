<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortalController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('home.html.twig', ['page'=>'Главная страница']);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('home_admin.html.twig', ['page'=>'Админка']);
    }

    #[Route ("/article/{slug}", name: "app_article_detale")]
    public function detail(Article $article) {

        return $this->render('detail.html.twig', [
            'article' => $article,
            'page' => $article->getTitle()
        ]);
    }

    #[Route("/articlee/all", name: "app_article_all")]
    public function allArticles(ArticleRepository $articleRepository){
        $arts = $articleRepository->findLatestArticle();

        return $this->render('pages/all_article.html.twig', [
            'arts' => $arts,
            'page' => "Все записи",
        ]);
    }
}
