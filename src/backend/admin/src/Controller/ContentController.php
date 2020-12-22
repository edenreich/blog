<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        if ($this->request->isXmlHttpRequest()) {
            $client = new Client(['base_uri' => 'https://admin.eden-reich.com']);
            $response = $client->get('/articles');
            $articles = json_decode($response->getBody(), true);
            $articles = array_map(function($article) {
                return [
                    'id' => (int) $article['id'],
                    'title' => $article['title'],
                    'slug' => $article['slug'],
                    'meta_keywords' => $article['meta_keywords'],
                    'meta_description' => $article['meta_description'],
                    'published' => $article['published'],
                    'created_at' => $article['created_at'],
                    'updated_at' => $article['updated_at'],
                ];
            }, $articles);
            return new JsonResponse($articles);
        }

        return $this->render('content/list.html.twig');
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
