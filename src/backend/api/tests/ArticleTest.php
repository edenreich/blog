<?php

namespace App\Tests;

use App\Entity\Article;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleTest extends KernelTestCase
{
    private const BASE_URI = 'http://127.0.0.1:8080';

    /**
     * Store the guzzle http client.
     */
    private Client $client;

    /**
     * Store the entity manager.
     */
    private EntityManager $entityManager;

    /**
     * Setup a client and entity manager.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->client = new Client(['base_uri' => self::BASE_URI]);
    }

    /**
     * Unset the client and the entity manager.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client);
        unset($this->entityManager);
    }

    public function testCanFetchAllArticles(): void
    {
        $response = $this->client->get('/articles');

        $articles = json_decode($response->getBody());
        $this->assertCount(10, $articles);
    }

    public function testCanFetchSingleArticle(): void
    {
        /** @var Article */
        $article = $this->entityManager->getRepository(Article::class)->findAll()[0];
        $title = $article->getTitle();

        $response = $this->client->get(sprintf('/articles/%s', $article->getId()));

        $article = json_decode($response->getBody());
        $this->assertEquals($title, $article->title);
    }

    public function testGetting404EmptyResponseWhenNoArticleFound(): void
    {
        try {
            $response = $this->client->get('/articles/nonexistingarticle');
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
        $articlesResponse = json_decode($this->client->get('/articles')->getBody());

        $this->assertEquals($articles[0]->getId(), $articlesResponse[0]->id);
        $this->assertEquals($articles[1]->getId(), $articlesResponse[1]->id);
        $this->assertEquals($articles[3]->getId(), $articlesResponse[3]->id);
    }

    public function testCanFetchASingleArticleBySlug(): void
    {
        /** @var Article */
        $article = $this->entityManager->getRepository(Article::class)->findAll()[0];
        $title = $article->getId();

        $response = $this->client->get(sprintf('/articles/%s', $article->getSlug()));

        $article = json_decode($response->getBody());
        $this->assertEquals($title, $article->id);
    }
}
