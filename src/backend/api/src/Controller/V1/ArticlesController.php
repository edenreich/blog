<?php

namespace App\Controller\V1;

use Exception;
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
                    throw new Exception(sprintf('could not find article with slug or id %s', $id));
                }
            }

            return $this->json($article, 200, ['groups' => ['admin', 'frontend']]);
        } catch (Exception $exception) {
            return $this->json(['message' => sprintf('could not find article with slug or id %s', $id)], 404, ['groups' => ['admin', 'frontend']]);
        }
    }

    /**
     * @Route("/articles/{id}", methods={"DELETE", "OPTIONS"}, name="articles.delete")
     */
    public function delete(string $id, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            /** @var \App\Repository\ArticleRepository */
            $articleRepository = $entityManager->getRepository(Article::class);
            $affectedRow = $articleRepository->delete($id);

            if (!$affectedRow) {
                throw new Exception(sprintf('could not find or delete article with id %s', $id));
            }

            return $this->json([], 204, ['groups' => ['admin', 'frontend']]);
        } catch (Exception $exception) {
            return $this->json(['message' => sprintf('could not find or delete article with id %s', $id)], 404, ['groups' => ['admin', 'frontend']]);
        }
    }
}
