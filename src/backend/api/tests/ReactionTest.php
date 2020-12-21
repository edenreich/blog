<?php

namespace App\Tests;

use App\Entity\Article;
use App\Entity\Reaction;
use App\Entity\Session;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReactionTest extends KernelTestCase
{
    private const BASE_URI = 'http://127.0.0.1';

    /**
     * Store the guzzle http client.
     *
     * @var Client
     */
    private $client;

    /**
     * Setup a client.
     */
    protected function setUp(): void
    {
        parent::tearDown();
        $this->client = new Client(['base_uri' => self::BASE_URI]);
    }

    /**
     * Unset the client.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client);
    }

    public function testCanLikeArticle(): void
    {
        /** @var \Doctrine\ORM\EntityManager */
        $em = static::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var \App\Repository\SessionRepository */
        $sessionRepository = $em->getRepository(Session::class);
        $sessions = $sessionRepository->findAll();
        $session = $sessions[mt_rand(0, 9)];

        /** @var \App\Repository\ArticleRepository */
        $articleRepository = $em->getRepository(Article::class);
        $articles = $articleRepository->findAll();
        $article = $articles[mt_rand(0, 9)];

        $response = $this->client->post('/reactions', [
            RequestOptions::JSON => [
                'session' => $session->getId(),
                'article' => $article->getId(),
                'type' => 'like',
            ],
        ]);
        $reactionResponse = json_decode($response->getBody());

        /** @var \App\Repository\ReactionRepository */
        $reactionRepository = $em->getRepository(Reaction::class);
        $reaction = $reactionRepository->findOneBy(['session' => $session, 'article' => $article]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($reaction->getId(), $reactionResponse->id);
        $this->assertEquals('like', $reaction->getType());
        $this->assertEquals('like', $reactionResponse->type);
        $this->assertEquals($reaction->getType(), $reactionResponse->type);
    }

    public function testCanLoveArticle(): void
    {
        /** @var \Doctrine\ORM\EntityManager */
        $em = static::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var \App\Repository\SessionRepository */
        $sessionRepository = $em->getRepository(Session::class);
        $sessions = $sessionRepository->findAll();
        $session = $sessions[mt_rand(0, 9)];

        /** @var \App\Repository\ArticleRepository */
        $articleRepository = $em->getRepository(Article::class);
        $articles = $articleRepository->findAll();
        $article = $articles[mt_rand(0, 9)];

        $response = $this->client->post('/reactions', [
            RequestOptions::JSON => [
                'session' => $session->getId(),
                'article' => $article->getId(),
                'type' => 'love',
            ],
        ]);
        $reactionResponse = json_decode($response->getBody());

        /** @var \App\Repository\ReactionRepository */
        $reactionRepository = $em->getRepository(Reaction::class);
        $reaction = $reactionRepository->findOneBy(['session' => $session, 'article' => $article]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($reaction->getId(), $reactionResponse->id);
        $this->assertEquals('love', $reaction->getType());
        $this->assertEquals('love', $reactionResponse->type);
        $this->assertEquals($reaction->getType(), $reactionResponse->type);
    }

    public function testCanDislikeArticle(): void
    {
        /** @var \Doctrine\ORM\EntityManager */
        $em = static::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var \App\Repository\SessionRepository */
        $sessionRepository = $em->getRepository(Session::class);
        $sessions = $sessionRepository->findAll();
        $session = $sessions[mt_rand(0, 9)];

        /** @var \App\Repository\ArticleRepository */
        $articleRepository = $em->getRepository(Article::class);
        $articles = $articleRepository->findAll();
        $article = $articles[mt_rand(0, 9)];

        $response = $this->client->post('/reactions', [
            RequestOptions::JSON => [
                'session' => $session->getId(),
                'article' => $article->getId(),
                'type' => 'dislike',
            ],
        ]);
        $reactionResponse = json_decode($response->getBody());

        /** @var \App\Repository\ReactionRepository */
        $reactionRepository = $em->getRepository(Reaction::class);
        $reaction = $reactionRepository->findOneBy(['session' => $session, 'article' => $article]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($reaction->getId(), $reactionResponse->id);
        $this->assertEquals('dislike', $reaction->getType());
        $this->assertEquals('dislike', $reactionResponse->type);
        $this->assertEquals($reaction->getType(), $reactionResponse->type);
    }

    public function testCanChangeReactionToArticle(): void
    {
        /** @var \Doctrine\ORM\EntityManager */
        $em = static::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var \App\Repository\SessionRepository */
        $sessionRepository = $em->getRepository(Session::class);
        $sessions = $sessionRepository->findAll();
        $session = $sessions[mt_rand(0, 9)];

        /** @var \App\Repository\ArticleRepository */
        $articleRepository = $em->getRepository(Article::class);
        $articles = $articleRepository->findAll();
        $article = $articles[mt_rand(0, 9)];

        // dislike the article
        $this->client->post('/reactions', [
            RequestOptions::JSON => [
                'session' => $session->getId(),
                'article' => $article->getId(),
                'type' => 'dislike',
            ],
        ]);

        // changed my mind like the article
        $this->client->post('/reactions', [
            RequestOptions::JSON => [
                'session' => $session->getId(),
                'article' => $article->getId(),
                'type' => 'like',
            ],
        ]);

        /** @var \App\Repository\ReactionRepository */
        $reactionRepository = $em->getRepository(Reaction::class);
        $reactions = $reactionRepository->findBy(['session' => $session, 'article' => $article]);

        $this->assertCount(1, $reactions);
        $this->assertEquals('like', $reactions[0]->getType());
    }

    public function testCanFetchCountOfAllReactionsForASpecificArticle(): void
    {
        /** @var \Doctrine\ORM\EntityManager */
        $em = static::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var \App\Repository\ArticleRepository */
        $articleRepository = $em->getRepository(Article::class);
        $articles = $articleRepository->findAll();
        $article = $articles[mt_rand(0, 9)];

        $response = $this->client->get('/reactions/count?article='.$article->getId(), [
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json',
            ],
        ]);
        $reactions = json_decode($response->getBody(), true);

        $expectedCount = [
            'like' => 0,
            'love' => 0,
            'dislike' => 0,
        ];
        $article->getReactions()->forAll(function (int $index, Reaction $reaction) use (&$expectedCount) {
            ++$expectedCount[$reaction->getType()];
        });

        $this->assertEquals($expectedCount, $reactions);
    }
}