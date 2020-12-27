<?php

namespace App\Controller\V1;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", methods={"GET", "OPTIONS"}, name="articles.list")
     */
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->json($articles, 200, ['groups' => ['admin', 'frontend']]);
    }

    /**
     * @Route("/articles/{id}", methods={"GET", "OPTIONS"}, name="articles.find")
     */
    public function find(string $id, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $article = $entityManager->getRepository(Article::class)->findOneBy(['slug' => $id]);

            if (!$article) {
                $article = $entityManager->getRepository(Article::class)->findOneBy(['id' => $id]);

                if (!$article) {
                    throw new \Exception(sprintf('could not find article with slug or id %s', $id));
                }
            }
        } catch (\Exception $exception) {
            return $this->json(['message' => sprintf('could not find article with slug or id %s', $id)], 404);
        }

        return $this->json($article, 200, ['groups' => ['admin', 'frontend']]);
    }
}
