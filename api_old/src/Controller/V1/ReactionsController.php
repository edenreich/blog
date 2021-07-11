<?php

namespace App\Controller\V1;

use App\Controller\TokenAuthenticatedController;
use App\Entity\Article;
use App\Entity\Reaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReactionsController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @Route("/reactions", methods={"POST"}, name="reactions.create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        /** @var \App\Repository\ReactionRepository */
        $reactionsRepository = $entityManager->getRepository(Reaction::class);
        $reaction = $reactionsRepository->store($data);

        return $this->json($reaction, 201, [], ['groups' => ['admin', 'frontend']]);
    }

    /**
     * @Route("/reactions/count", methods={"GET"}, name="reactions.count")
     */
    public function count(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $articleId = $request->query->get('article');

        /** @var \App\Repository\ArticleRepository */
        $articlesRepository = $entityManager->getRepository(Article::class);
        $article = $articlesRepository->findOneBy(['id' => $articleId]);

        if (!$articleId) {
            return $this->json(['message' => sprintf('Could not find article id %s', $articleId)], 404);
        }

        $count = [
            'like' => 0,
            'love' => 0,
            'dislike' => 0,
        ];
        $article->getReactions()->forAll(function (int $index, Reaction $reaction) use (&$count) {
            ++$count[$reaction->getType()];
        });

        return $this->json($count, 200);
    }
}
