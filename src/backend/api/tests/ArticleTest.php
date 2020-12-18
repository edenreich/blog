<?php

namespace App\Tests;

use App\Entity\Article;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ArticleTest extends KernelTestCase
{
    public function testCanFetchAllArticles()
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1']);
        
        $response = $client->get('/articles');

        $articles = json_decode($response->getBody());
        $this->assertCount(10, $articles);
    }

    public function testCanFetchSingleArticle()
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1']);
        
        /** @var EntityManager */
        $em = self::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var Article */
        $article = $em->getRepository(Article::class)->findAll()[0];
        $title = $article->getTitle();

        $response = $client->get(sprintf('/articles/%s', $article->getId()));

        $article = json_decode($response->getBody());
        $this->assertEquals($title, $article->title);
    }

    public function testGetting404EmptyResponseWhenNoArticleFound()
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1']);
        
        /** @var EntityManager */
        $em = self::bootKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        try {
            $response = $client->get('/articles/nonexistingarticle');
            $this->assertNotEquals(200, $response->getStatusCode());
        } catch (ClientException $exception) {
            $this->assertEquals(404, $exception->getResponse()->getStatusCode());
            $this->assertEquals('could not find article with id nonexistingarticle', json_decode($exception->getResponse()->getBody())->message);
        }
    }
}
