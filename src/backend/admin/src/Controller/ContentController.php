<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends CategoriesAwareController
{
    /**
     * @Route("/content", methods={"GET"}, name="categories_content")
     */
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('list_content');
    }

    /**
     * @Route("/content/list", methods={"GET"}, name="list_content")
     */
    public function list(): Response
    {
        // @todo send an api request to fetch all articles.
        $articles = [];

        return $this->render('content/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/content/{id}/edit", methods={"GET"}, name="content_edit")
     */
    public function edit(string $id): Response
    {
        return $this->render('content/edit.html.twig', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/content/create", methods={"GET"}, name="content_create")
     */
    public function create(): Response
    {
        return $this->render('content/create.html.twig');
    }
}
