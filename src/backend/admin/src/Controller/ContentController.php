<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends CategoriesAwareController
{
    /**
     * @Route("/content", name="categories_content")
     */
    public function index(): Response
    {
        return $this->render('content/index.html.twig', [
            'categories' => $this->categories,
        ]);
    }
}
