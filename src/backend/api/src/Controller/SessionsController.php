<?php

namespace App\Controller;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SessionsController extends AbstractController
{
    /**
     * Find or create a new session.
     *
     * @Route("/sessions", methods={"POST", "OPTIONS"}, name="sessions.create")
     *
     * @return Response|JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $clientIp = $request->query->get('ip_address');

        /** @var \App\Repository\SessionRepository */
        $repository = $entityManager->getRepository(Session::class);

        try {
            $session = $repository->store($clientIp);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => sprintf('Could not create the session for ip %s', $clientIp)], 422);
        }

        return new Response($serializer->serialize($session, 'json', ['groups' => ['admin', 'frontend']]), 201, ['content-type' => 'application/json']);
    }

    /**
     * Find a single session by given id.
     *
     * @Route("/sessions/{id}", methods={"GET", "OPTIONS"}, name="sessions.find")
     *
     * @param Request $request
     *
     * @return Response|JsonResponse
     */
    public function find(string $id, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Repository\SessionRepository */
        $repository = $entityManager->getRepository(Session::class);

        try {
            $session = $repository->findOneBy(['id' => $id]);

            if (!$session) {
                throw new \Exception(sprintf('Could not find session id %s', $id));
            }
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => sprintf('Could not find session id %s', $id)], 404);
        }

        return new Response($serializer->serialize($session, 'json', ['groups' => ['admin', 'frontend']]), 200, ['content-type' => 'application/json']);
    }

    /**
     * Find a session by given query param ip_address.
     *
     * @Route("/sessions", methods={"GET", "OPTIONS"}, name="sessions.find_by_ip")
     *
     * @return Response|JsonResponse
     */
    public function findByIpAddress(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $clientIp = $request->query->get('ip_address');

        /** @var \App\Repository\SessionRepository */
        $repository = $entityManager->getRepository(Session::class);

        try {
            $session = $repository->findOneBy(['ipAddress' => $clientIp]);

            if (!$session) {
                throw new \Exception(sprintf('Could not find session for ip %s', $clientIp));
            }
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => sprintf('Could not find session for ip %s', $clientIp)], 404);
        }

        return new Response($serializer->serialize($session, 'json', ['groups' => ['admin', 'frontend']]), 200, ['content-type' => 'application/json']);
    }
}
