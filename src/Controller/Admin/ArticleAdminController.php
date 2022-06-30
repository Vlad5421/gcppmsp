<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
//use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    #[Route('/admin/article/create', name: 'app_admin_article_create')]
    public function create(EntityManagerInterface $em, Request $request ): Response
    {
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){

            $article = $this->handleFormRequest($form);
            $em->persist($article);
            $em->flush();

            $this->addFlash('flash_message', 'Статья изенена');

            return $this->redirectToRoute('app_admin_article_create', ['id' => $article->getId()]);
        }


        return $this->render('admin/article_admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function handleFormRequest($form)
    {
        /** @var Article $article */
        $article = $form->getData();
        $article
            ->setAutor($this->getUser())
        ;
/*
        /** @var UploadedFile|null $image */
/*        $image = $form->get('image')->getData();

        if ($image) {
            $fileName = $articleFileUploader->uploadFile($image, $article->getImageFilename());
            $article->setImageFilename($fileName);
        } */
        return $article;

    }
}
