<?php

namespace App\Tests;

use App\Entity\Article;
use DateTime;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;

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

    public function testCanCreateAnArticle(): void
    {
        $payload = [
            'title' => 'Test Article',
            'slug' => 'test-article',
            'meta_keywords' => 'test, testing',
            'meta_description' => 'This is a test article',
            'content' => '<html><head><title>Test Article</title></head><body><p>This is a test article</p></body></html>',
        ];

        $response = $this->client->post('articles', [
            RequestOptions::JSON => $payload,
        ]);

        $responseArticle = json_decode($response->getBody(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('id', $responseArticle);
        $this->assertArrayHasKey('title', $responseArticle);
        $this->assertArrayHasKey('slug', $responseArticle);
        $this->assertArrayHasKey('meta_keywords', $responseArticle);
        $this->assertArrayHasKey('meta_description', $responseArticle);
        $this->assertArrayHasKey('content', $responseArticle);
        $this->assertArrayHasKey('published_at', $responseArticle);
        $this->assertArrayHasKey('deleted_at', $responseArticle);
        $this->assertArrayHasKey('updated_at', $responseArticle);
        $this->assertArrayHasKey('created_at', $responseArticle);
        $this->assertEquals($payload['title'], $responseArticle['title']);
        $this->assertEquals($payload['slug'], $responseArticle['slug']);
        $this->assertEquals($payload['meta_keywords'], $responseArticle['meta_keywords']);
        $this->assertEquals($payload['meta_description'], $responseArticle['meta_description']);
        $this->assertEquals($payload['content'], $responseArticle['content']);
        $this->assertNull($responseArticle['published_at']);
        $this->assertNull($responseArticle['deleted_at']);
        $this->assertNull($responseArticle['updated_at']);
        $this->assertNotNull($responseArticle['created_at']);
    }

    public function testCanUpdateExistingArticle(): void
    {
        $response = $this->client->get('articles');
        $article = json_decode($response->getBody(), true)[0];

        $payload = [
            'title' => 'Test Article',
            'slug' => 'test-article',
            'meta_keywords' => 'test, testing',
            'meta_description' => 'This is a test article',
            'content' => '<html><head><title>Test Article</title></head><body><p>This is a test article</p></body></html>',
        ];

        $response = $this->client->put(sprintf('articles/%s', $article['id']), [
            RequestOptions::JSON => $payload,
        ]);

        $responseArticle = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('id', $responseArticle);
        $this->assertArrayHasKey('title', $responseArticle);
        $this->assertArrayHasKey('slug', $responseArticle);
        $this->assertArrayHasKey('meta_keywords', $responseArticle);
        $this->assertArrayHasKey('meta_description', $responseArticle);
        $this->assertArrayHasKey('content', $responseArticle);
        $this->assertArrayHasKey('published_at', $responseArticle);
        $this->assertArrayHasKey('deleted_at', $responseArticle);
        $this->assertArrayHasKey('updated_at', $responseArticle);
        $this->assertArrayHasKey('created_at', $responseArticle);
        $this->assertEquals($article['id'], $responseArticle['id']);
        $this->assertNotEquals($article['title'], $responseArticle['title']);
        $this->assertEquals($payload['title'], $responseArticle['title']);
        $this->assertEquals($payload['slug'], $responseArticle['slug']);
        $this->assertEquals($payload['meta_keywords'], $responseArticle['meta_keywords']);
        $this->assertEquals($payload['meta_description'], $responseArticle['meta_description']);
        $this->assertEquals($payload['content'], $responseArticle['content']);
        $this->assertNull($responseArticle['published_at']);
        $this->assertNull($responseArticle['deleted_at']);
        $this->assertNotNull($responseArticle['updated_at']);
        $this->assertNotNull($responseArticle['created_at']);
    }

    public function testCanFetchOnlyPublishedArticles(): void
    {
        $response = $this->client->get('articles/?filter=published_only');
        $articles = json_decode($response->getBody(), true);

        foreach ($articles as $article) {
            $this->assertNotNull($article['published_at']);
        }
    }

    public function testCanFetchOnlyUpcomingArticles(): void
    {
        $response = $this->client->get('articles/?filter=upcoming_only');
        $articles = json_decode($response->getBody(), true);

        foreach ($articles as $article) {
            $this->assertNull($article['published_at']);
        }
    }
}
