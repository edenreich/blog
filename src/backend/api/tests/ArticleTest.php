<?php

namespace App\Tests;

use DateTime;
use App\Entity\Article;
use GuzzleHttp\Exception\ClientException;

class ArticleTest extends AbstractTestCase
{
    public function testCanFetchAllArticles(): void
    {
        $response = $this->client->get('articles');
        $articles = json_decode($response->getBody());

        $this->assertCount(10, $articles);
    }

    public function testFetchingOnlyNonDeletedArticles(): void
    {
        /** @var Article */
        $article = $this->entityManager->getRepository(Article::class)->findAll()[0];

        $article->setDeletedAt(new DateTime());
        $this->entityManager->flush();

        $response = $this->client->get('articles');
        $articles = json_decode($response->getBody());

        $this->assertCount(9, $articles);
    }

    public function testCanFetchSingleArticle(): void
    {
        /** @var Article */
        $article = $this->entityManager->getRepository(Article::class)->findAll()[0];
        $title = $article->getTitle();

        $response = $this->client->get(sprintf('articles/%s', $article->getId()));

        $article = json_decode($response->getBody());
        $this->assertEquals($title, $article->title);
    }

    public function testGetting404EmptyResponseWhenNoArticleFound(): void
    {
        try {
            $response = $this->client->get('articles/nonexistingarticle');
            $this->assertNotEquals(200, $response->getStatusCode());
        } catch (ClientException $exception) {
            $this->assertEquals(404, $exception->getResponse()->getStatusCode());
            $this->assertEquals('could not find article with slug or id nonexistingarticle', json_decode($exception->getResponse()->getBody())->message);
        }
    }

    public function testFetchArticlesInDescendingOrderByDefault(): void
    {
        /** @var Article[] */
        $articles = $this->entityManager->getRepository(Article::class)->findAll();
        usort($articles, function (Article $first, Article $second) {
            return $first->getCreatedAt() < $second->getCreatedAt();
        });

        /** @var Article[] */
        $articlesResponse = json_decode($this->client->get('articles')->getBody());

        $this->assertEquals($articles[0]->getId(), $articlesResponse[0]->id);
        $this->assertEquals($articles[1]->getId(), $articlesResponse[1]->id);
        $this->assertEquals($articles[3]->getId(), $articlesResponse[3]->id);
    }

    public function testCanFetchASingleArticleBySlug(): void
    {
        /** @var Article */
        $article = $this->entityManager->getRepository(Article::class)->findAll()[0];
        $id = $article->getId();

        $response = $this->client->get(sprintf('articles/%s', $article->getSlug()));

        $article = json_decode($response->getBody());
        $this->assertEquals($id, $article->id);
    }

    public function testCanDeleteAnArticle(): void
    {
        /** @var Article */
        $article = $this->entityManager->getRepository(Article::class)->findAll()[0];

        $response = $this->client->delete(sprintf('articles/%s', $article->getId()));
        $this->entityManager->refresh($article);

        $content = json_decode($response->getBody());

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertNull($content);
        $this->assertNotNull($article->getDeletedAt());
    }
}
