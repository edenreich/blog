<?php

namespace App\Controller\V1;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", methods={"GET"}, name="articles.list")
     */
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var \App\Repository\ArticleRepository */
        $articleRepository = $entityManager->getRepository(Article::class);

        $articles = $articleRepository->findAll();

        return $this->json($articles, 200, [], ['groups' => ['admin', 'frontend']]);
    }

    /**
     * @Route("/articles/{id}", methods={"GET"}, name="articles.find")
     */
    public function find(string $id, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            /** @var \App\Repository\ArticleRepository */
            $articlesRepository = $entityManager->getRepository(Article::class);

            if ($this->isValidUuid($id)) {
                $article = $articlesRepository->findOneBy(['id' => $id]);
            } else {
                $article = $articlesRepository->findOneBy(['slug' => $id]);
            }
   
            if (!$article) {
                throw new Exception(sprintf('could not find article with slug or id %s', $id));
            }

            return $this->json($article, 200, [], ['groups' => ['admin', 'frontend']]);
        } catch (Exception $exception) {
            return $this->json(['message' => sprintf('could not find article with slug or id %s', $id)], 404, [], ['groups' => ['admin', 'frontend']]);
        }
    }

    /**
     * @Route("/articles/{id}", methods={"DELETE"}, name="articles.delete")
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

            return $this->json([], 204, [], ['groups' => ['admin', 'frontend']]);
        } catch (Exception $exception) {
            return $this->json(['message' => sprintf('could not find or delete article with id %s', $id)], 404, [], ['groups' => ['admin', 'frontend']]);
        }
    }

    /**
     * @Route("/articles", methods={"POST"}, name="articles.store")
     */
    public function store(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            /** @var \App\Repository\ArticleRepository */
            $articleRepository = $entityManager->getRepository(Article::class);
            $article = $articleRepository->store(json_decode($request->getContent(), true));

            return $this->json($article, 201, [], ['groups' => ['admin', 'frontend']]);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 422, [], ['groups' => ['admin', 'frontend']]);
        }
    }

    /**
     * @Route("/articles/{id}", methods={"PUT", "PATCH"}, name="articles.update")
     */
    public function update(string $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            /** @var \App\Repository\ArticleRepository */
            $articleRepository = $entityManager->getRepository(Article::class);
            $article = $articleRepository->update($id, json_decode($request->getContent(), true));

            return $this->json($article, 200, [], ['groups' => ['admin', 'frontend']]);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 422, [], ['groups' => ['admin', 'frontend']]);
        }
    }

    /**
     * Check if given string is uuid.
     */
    private function isValidUuid(string $uuid): bool
    {
        if (1 !== preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid)) {
            return false;
        }

        return true;
    }
}
