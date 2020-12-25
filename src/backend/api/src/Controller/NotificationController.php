<?php

namespace App\Controller;

use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class NotificationController extends AbstractController
{
    /**
     * create or update a notification.
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
}
