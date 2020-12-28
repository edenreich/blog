<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthcheckController extends AbstractController
{
    /**
     * @Route("/healthcheck", methods={"GET","OPTIONS"}, name="healthcheck")
     */
    public function health(): Response
    {
        return $this->json([
            'message' => 'healthy',
        ], 200);
    }
}
