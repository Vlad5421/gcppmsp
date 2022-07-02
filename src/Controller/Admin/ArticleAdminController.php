<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
//use App\Services\FileUploader;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    #[Route('/admin/article/create', name: 'app_admin_article_create')]
    public function create(EntityManagerInterface $em, Request $request, FileUploader $articleFileUploader ): Response
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){

            $article = $this->handleFormRequest($articleFileUploader, $form);


            $em->persist($article);
            $em->flush();

            $this->addFlash('flash_message', 'Статья создана');

            return $this->redirectToRoute('app_admin_article_create', ['id' => $article->getId()]);
        }


        return $this->render('admin/article_admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function handleFormRequest(FileUploader $fileUploader, $form)
    {
        /** @var Article $article */
        $article = $form->getData();
        $article
            ->setAutor($this->getUser())
        ;

        /** @var UploadedFile|null $image */
        $image = $form->get('mainImage')->getData();

        if ($image) {
            $fileName = $fileUploader->uploadFile($image, $article->getMainImage());
            $article->setMainImage($fileName);
        }
        return $article;

    }
}
