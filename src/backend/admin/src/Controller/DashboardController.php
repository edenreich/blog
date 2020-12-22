<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends NavigationAwareController
{
    /**
     * @Route("/admin", methods={"GET"}, name="admin")
     */
    public function admin(): RedirectResponse
    {
        return $this->redirectToRoute('navigation_parent_dashboard');
    }

    /**
     * @Route("/dashboard", methods={"GET"}, name="navigation_parent_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }
}
