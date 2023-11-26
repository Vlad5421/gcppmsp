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
        return $this->render('home.html.twig', ['page'=>'Главная страница']);
    }

    #[Route('/manage-panel', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('admin/admin_index.html.twig', ['page'=>'Админка']);
    }

//    #[Route ("/article/{id}", name: "app_article_detale")]
//    public function detail(Article $article, ImageGalleryRepository $igRepo) {
//
//        $imgCollection = $igRepo->findBy(["imageCollection"=>$article->getImageCollection()]);
//        return $this->render('detail.html.twig', [
//            'article' => $article,
//            'page' => $article->getTitle(),
//            'imgCollection' => $imgCollection,
//        ]);
//    }
//
//    #[Route("/articlee/all", name: "app_article_all")]
//    public function allArticles(ArticleRepository $articleRepository){
//        $arts = $articleRepository->findLatestArticle();
//
//        return $this->render('pages/all_article.html.twig', [
//            'arts' => $arts,
//            'page' => "Все записи",
//        ]);
//    }
}
