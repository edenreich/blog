<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends CategoriesAwareController
{
    /**
     * @Route("/admin", methods={"GET"}, name="admin")
     */
    public function admin(): RedirectResponse
    {
        return $this->redirectToRoute('categories_dashboard');
    }

    /**
     * @Route("/dashboard", methods={"GET"}, name="categories_dashboard")
     */
    public function dashboard(): RedirectResponse
    {
        return $this->redirectToRoute('dashboard_list');
    }

    /**
     * @Route("/dashboard/list", methods={"GET"}, name="dashboard_list")
     */
    public function list(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }
}
