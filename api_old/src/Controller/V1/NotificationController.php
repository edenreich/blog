<?php

namespace App\Controller\V1;

use App\Controller\TokenAuthenticatedController;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * Get all notifications.
     *
     * @Route("/notifications", methods={"GET"}, name="notifications.all")
     */
    public function all(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $sessionId = $request->get('session_id');

        /** @var \App\Repository\NotificationRepository */
        $repository = $entityManager->getRepository(Notification::class);

        try {
            if ($sessionId) {
                $notification = $repository->findOneBy(['session' => $sessionId]);

                return $this->json($notification, 200, [], ['groups' => ['admin', 'frontend']]);
            } else {
                $notifications = $repository->findAll();

                return $this->json($notifications, 200, [], ['groups' => ['admin', 'frontend']]);
            }
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), 404);
        }
    }

    /**
     * Create or update a notification.
     *
     * @Route("/notifications", methods={"POST"}, name="notifications.create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        /** @var \App\Repository\NotificationRepository */
        $repository = $entityManager->getRepository(Notification::class);

        try {
            $notification = $repository->store($body);
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), 422);
        }

        return $this->json($notification, 201, [], ['groups' => ['admin', 'frontend']]);
    }

    /**
     * Update a notification.
     *
     * @Route("/notifications/{id}", methods={"PUT"}, name="notifications.update")
     */
    public function update(string $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        /** @var \App\Repository\NotificationRepository */
        $repository = $entityManager->getRepository(Notification::class);

        try {
            $notification = $repository->update($id, $body);
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), 422);
        }

        return $this->json($notification, 200, [], ['groups' => ['admin', 'frontend']]);
    }
}
