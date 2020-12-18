<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function list(SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return new Response($serializer->serialize($articles, 'json', ['groups' => ['admin', 'frontend']]));
    }

    /**
     * @Route("/articles/{id}", name="article")
     */
    public function find(string $id, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        try {
            $article = $entityManager->getRepository(Article::class)->findOneBy(['id' => $id]);

            if (!$article) {
                throw new \Exception(sprintf('could not find article with id %s', $id));
            }
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => sprintf('could not find article with id %s', $id)], 404);
        }

        return new Response($serializer->serialize($article, 'json', ['groups' => ['admin', 'frontend']]));
    }
}
