<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'articles')]
    public function index(SerializerInterface $serializer): Response
    {
        $article = new Article;

        $article->setTitle("test");
        $article->setContent("testing..");
        $article->setSlug("test");

        return new Response($serializer->serialize($article, 'json'));
    }
}
