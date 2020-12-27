<?php

namespace App\Controller\V1;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    /**
     * Create or update a notification.
     *
     * @Route("/notifications", methods={"POST", "OPTIONS"}, name="notifications.create")
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

        return $this->json($notification, 201, ['groups' => ['admin', 'frontend']]);
    }

    /**
     * Update a notification.
     *
     * @Route("/notifications/{id}", methods={"PUT", "OPTIONS"}, name="notifications.update")
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

        return $this->json($notification, 200, ['groups' => ['admin', 'frontend']]);
    }
}
