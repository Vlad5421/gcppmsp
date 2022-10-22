<?php

namespace App\Controller\Admin\ArticleAdmin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    #[Route('/admin/article', name: 'app_admin_article')]
    public function index(): Response
    {
        return $this->render('admin/article_admin/index.html.twig', [
            'controller_name' => 'ArticleAdminController',
            "page" => "Все записи",
        ]);
    }

    #[Route('/admin/article/create', name: 'app_admin_article_create')]
    public function create(Request $request, EntityManagerInterface $em, FileUploader $galleryFileUploader): Response
    {
        $form = $this->createForm(ArticleFormType::class, new Article());
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){

            $service = $this->handleFormRequest($galleryFileUploader, $form);

            $em->persist($service);
            $em->flush();
            $this->addFlash('flash_message', 'Страница создана');

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

    public function handleFormRequest(FileUploader $galleryFileUploader, $form)
    {
        /** @var Article $article */
        $article = $form->getData();

        /** @var UploadedFile|null $image */
        $image = $form->get('image')->getData();

        if ($image) {
            $fileName = $galleryFileUploader->uploadFile($image, $article->getMainImage());
            $article->setMainImage($fileName);
        }
        return $article;

    }
}
