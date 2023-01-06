<?php

namespace App\Controller\Admin\ArticleAdmin;

use App\Entity\Article;
use App\Entity\Collections;
use App\Entity\ImageGallery;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    #[Route('/admin/article/edit/{id}', name: 'app_admin_article_edit')]
    public function edit(Article $article, Request $request, EntityManagerInterface $em, FileUploader $galleryFileUploader): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->saveArticle($galleryFileUploader, $form, $em);
            return $this->redirectToRoute('app_admin_article_create');
        }
        $type = "page";
        if ($request->query->get("type"))
            $type = $request->query->get("type");

        return $this->render('admin/article_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать страницу',
            'article_type' => $type,
        ]);
    }

    #[Route('/admin/article/create', name: 'app_admin_article_create')]
    public function create(Request $request, EntityManagerInterface $em, FileUploader $galleryFileUploader): Response
    {
        $form = $this->createForm(ArticleFormType::class, new Article());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->saveArticle($galleryFileUploader, $form, $em);
            return $this->redirectToRoute('app_admin_article_create');
        }

        $type = "page";
        if ($request->query->get("type"))
            $type = $request->query->get("type");

        return $this->render('admin/article_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать страницу',
            'article_type' => $type,
        ]);
    }

    public function saveArticle($galleryFileUploader, $form, $em)
    {
        $article = $this->handleFormRequest($galleryFileUploader, $form, $em);
        $em->persist($article);
        $em->flush();
        $this->addFlash('flash_message', 'Страница создана');
        return true;
    }
    public function createCollection(Article $article, EntityManagerInterface $em): Collections
    {
        $coll = (new Collections())
            ->setName($article->getTitle()."-collection")
            ->setType('image')
        ;
        $em->persist($coll);
        $em->flush();
        return $coll;
    }

    public function createGallery(FileUploader $galleryFileUploader, $imgArray, $article, $em): ?Article
    {
        /** @var Collections $imgCollection */
        $imgCollection = $this->createCollection($article, $em);

        foreach ($imgArray as $file){
            /** @var UploadedFile $file */
            $fileName = $galleryFileUploader->uploadFile($file);
            $em->persist(( new ImageGallery())->setName($fileName)->setImageCollection($imgCollection->getId()));
        }
        $em->flush();
        $article->setImageCollection($imgCollection->getId());

        return $article;
    }

    public function handleFormRequest(FileUploader $galleryFileUploader, $form, $em): Article
    {
        /** @var Article $article */
        $article = $form->getData();

        /** @var UploadedFile|null $image */
        $image = $form->get('image')->getData();
        if ($image) {
            $fileName = $galleryFileUploader->uploadFile($image, $article->getMainImage());
            $article->setMainImage($fileName);
        }

        return $this->createGallery($galleryFileUploader, $form->get('imageCollection')->getData(), $article, $em);
    }

    #[
        Route('/admin/article/all', name: 'app_admin_article'),
        IsGranted('ROLE_ARTICLE_ADMIN')
    ]
    public function adminArticles(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $pagination = $paginator->paginate(
            $articleRepository->findAllWithSearch(
                $request->query->get('q'),
                $request->query->get('type'),
                $request->query->has('showDeleted')
            ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 10 /*limit per page*/
        );


        return $this->render('admin/article_admin/list_article.html.twig', [
            'collection' => $pagination,
            "page" => "Все записи",
        ]);
    }
}
