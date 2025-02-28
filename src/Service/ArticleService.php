<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use App\Entity\Article;

//index des articles pris du repo, FOR TEST PURPOSES
final class ArticleService
{
    public function __construct(private readonly ArticleRepository $articleRepository)
    {
    }

    /**
     * Fetch all articles.
     * 
     * @return Article[]
     */
    public function getAllArticles(): array
    {
        return $this->articleRepository->findAll();
    }
}
