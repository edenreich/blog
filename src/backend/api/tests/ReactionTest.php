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
        $em = static::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var \App\Repository\SessionRepository */
        $sessionRepository = $em->getRepository(Session::class);
        $sessions = $sessionRepository->findAll();
        $session = $sessions[mt_rand(0, 10)];

        /** @var \App\Repository\ArticleRepository */
        $articleRepository = $em->getRepository(Article::class);
        $articles = $articleRepository->findAll();
        $article = $articles[mt_rand(0, 10)];

        $response = $this->client->post('/reactions', [
            RequestOptions::FORM_PARAMS => [
                'session' => $session->getId(),
                'article' => $article->getId(),
                'type' => 'like',
            ],
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json'
            ]
        ]);
        $reaction = json_decode($response->getBody());

        /** @var \App\Repository\ReactionRepository */
        $reactionRepository = $em->getRepository(Reaction::class);
        $reaction = $reactionRepository->findOneBy(['session' => $session, 'article' => $article]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($reaction->getId(), $reaction->id);
    }

    public function testCanLoveArticle(): void
    {
        throw new \Exception('pending implemention');
    }

    public function testCanDislikeArticle(): void
    {
        throw new \Exception('pending implemention');
    }

    public function testIfCanChangeReaction(): void
    {
        throw new \Exception('pending implemention');
    }
}
