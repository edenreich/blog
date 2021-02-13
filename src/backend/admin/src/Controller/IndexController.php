<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends NavigationAwareController
{
    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('dashboard');
    }
}
