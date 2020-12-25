<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Reaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReactionsController extends AbstractController
{
    /**
     * @Route("/reactions", methods={"POST", "OPTIONS"}, name="reactions.create")
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        /** @var \App\Repository\ReactionRepository */
        $reactionsRepository = $entityManager->getRepository(Reaction::class);
        $reaction = $reactionsRepository->store($data);

        return new Response($serializer->serialize($reaction, 'json', ['groups' => ['admin', 'frontend']]), 201, ['content-type' => 'application/json']);
    }

    /**
     * @Route("/reactions/count", methods={"GET", "OPTIONS"}, name="reactions.count")
     */
    public function count(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleId = $request->query->get('article');

        /** @var \App\Repository\ArticleRepository */
        $articlesRepository = $entityManager->getRepository(Article::class);
        $article = $articlesRepository->findOneBy(['id' => $articleId]);

        if (!$articleId) {
            return new JsonResponse(['message' => sprintf('Could not find article id %s', $articleId)], 404);
        }

        $count = [
            'like' => 0,
            'love' => 0,
            'dislike' => 0,
        ];
        $article->getReactions()->forAll(function (int $index, Reaction $reaction) use (&$count) {
            ++$count[$reaction->getType()];
        });

        return new JsonResponse($count, 200);
    }
}
