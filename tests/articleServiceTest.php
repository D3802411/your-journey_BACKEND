<?php

namespace App\Tests\Service;

use PHPUNIT\Framework\TestCase;
use App\Service\ArticleService;
use App\Repository\ArticleRepository;
use App\Entity\Article;


final class ArticleServiceTest extends TestCase
{
    public ArticleRepository $articleRepository;
    public ArticleService $articleService;

    public function setUp(): void
    {
        $this->articleRepository = $this->createMock(ArticleRepository::class);
        $this->articleService = new ArticleService($this->articleRepository);
    }

    public function testGetAllArticlesReturnsArray(): void
    {
        $article1 = new Article();
        $article2 = new Article();

        $this->articleRepository->method('findAll')->willReturn([$article1, $article2]);

        $result = $this->articleService->getAllArticles();

        $this->assertCount(2, $result);
        $this->assertSame($article1, $result[0]);
        $this->assertSame($article2, $result[1]);
    }

    public function testGetAllArticlesReturnsEmptyArray(): void
    {
        $this->articleRepository->method('findAll')->willReturn([]);

        $result = $this->articleService->getAllArticles();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
