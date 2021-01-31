<?php

namespace App\Controller\V1;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionsController extends AbstractController
{
    /**
     * Find or create a new session.
     *
     * @Route("/sessions", methods={"POST"}, name="sessions.create")
     *
     * @return Response|JsonResponse
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $clientIp = $request->query->get('ip_address');

        /** @var \App\Repository\SessionRepository */
        $repository = $entityManager->getRepository(Session::class);

        try {
            $session = $repository->store($clientIp);
        } catch (Exception $exception) {
            return $this->json(['message' => sprintf('Could not create the session for ip %s', $clientIp)], 422);
        }

        return $this->json($session, 201, [], ['groups' => ['admin', 'frontend']]);
    }

    /**
     * Find a single session by given id.
     *
     * @Route("/sessions/{id}", methods={"GET"}, name="sessions.find")
     *
     * @param Request $request
     */
    public function find(string $id, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var \App\Repository\SessionRepository */
        $repository = $entityManager->getRepository(Session::class);

        try {
            $session = $repository->findOneBy(['id' => $id]);

            if (!$session) {
                throw new Exception(sprintf('Could not find session id %s', $id));
            }
        } catch (Exception $exception) {
            return $this->json(['message' => sprintf('Could not find session id %s', $id)], 404);
        }

        return $this->json($session, 200, [], ['groups' => ['admin', 'frontend']]);
    }

    /**
     * Find a session by given query param ip_address.
     *
     * @Route("/sessions", methods={"GET"}, name="sessions.find_by_ip")
     */
    public function findByIpAddress(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $clientIp = $request->query->get('ip_address');

        /** @var \App\Repository\SessionRepository */
        $repository = $entityManager->getRepository(Session::class);

        try {
            $session = $repository->findOneBy(['ipAddress' => $clientIp]);

            if (!$session) {
                throw new Exception(sprintf('Could not find session for ip %s', $clientIp));
            }
        } catch (Exception $exception) {
            return $this->json(['message' => sprintf('Could not find session for ip %s', $clientIp)], 404);
        }

        return $this->json($session, 200, [], ['groups' => ['admin', 'frontend']]);
    }
}
