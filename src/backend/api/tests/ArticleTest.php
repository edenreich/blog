<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ArticleTest extends TestCase
{
    public function testCanFetchAllArticles()
    {
        $client =  new Client(['base_uri' => 'http://127.0.0.1']);
        
        $response = $client->get('/articles');

        $articles = json_decode($response->getBody());
        $this->assertCount(10, $articles);
    }
}
