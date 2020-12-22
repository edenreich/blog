<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends CategoriesAwareController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(): RedirectResponse
    {
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/dashboard", name="categories_dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'categories' => $this->categories,
        ]);
    }
}
