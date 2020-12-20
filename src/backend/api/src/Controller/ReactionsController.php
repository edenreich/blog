<?php

namespace App\Controller;

use App\Entity\Reaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReactionsController extends AbstractController
{
    /**
     * @Route("/reactions", methods={"POST", "OPTIONS"}, name="reactions.create")
     */
    public function create(SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Repository\ReactionRepository */
        $reactionsRepository = $entityManager->getRepository(Reaction::class);
        $reaction = $reactionsRepository->store();

        return new Response($serializer->serialize($reaction, 'json', ['groups' => ['admin', 'frontend']]), 200, ['content-type' => 'application/json']);
    }
}
