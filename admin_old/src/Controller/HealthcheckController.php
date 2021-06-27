<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HealthcheckController extends AbstractController
{
    /**
     * @Route("/healthcheck", methods={"GET"}, name="healthcheck")
     */
    public function health(): JsonResponse
    {
        return $this->json([
            'message' => 'healthy',
        ], 200);
    }
}
