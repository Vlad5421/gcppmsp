<?php

namespace App\Twig;

use App\Repository\ArticleRepository;
use Twig\Extension\RuntimeExtensionInterface;

class AppGetPagesList implements RuntimeExtensionInterface
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    public function getPages()
    {
        return $this->articleRepository->findBy(['sector' => 3]);
    }

}