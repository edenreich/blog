<?php

namespace App\Controller;

use App\Dto\Article;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends NavigationAwareController
{
    /**
     * @Route("/content", methods={"GET"}, name="navigation_parent_content")
     */
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('navigation_sub_content_list');
    }

    /**
     * @Route("/content/list", methods={"GET"}, name="navigation_sub_content_list")
     */
    public function list(): Response
    {
        if ($this->request->isXmlHttpRequest()) {
            $client = new Client(['base_uri' => 'https://admin.eden-reich.com']);
            $response = $client->get('/articles');
            $responseArticles = json_decode($response->getBody(), true);
            $articles = array_map(function ($responseArticle) {
                return new Article($responseArticle);
            }, $responseArticles);

            return new JsonResponse($articles);
        }

        return $this->render('content/list.html.twig');
    }

    /**
     * @Route("/content/create", methods={"GET"}, name="navigation_sub_content_create")
     */
    public function create(): Response
    {
        return $this->render('content/create.html.twig');
    }

    /**
     * @Route("/content/{id}/edit", methods={"GET"}, name="content_edit")
     */
    public function edit(string $id): Response
    {
        // $client = new Client(['base_uri' => 'https://admin.eden-reich.com']);
        // $response = $client->get('/articles/' . $id);
        // $responseArticle = json_decode($response->getBody(), true);
        // $article = new Article($responseArticle);
        $article['id'] = $id;
        return $this->render('content/edit.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/content/{id}/delete", methods={"GET"}, name="content_delete")
     */
    public function delete(): Response
    {
        return $this->redirectToRoute('navigation_content_list');
    }

    /**
     * @Route("/content/{id}/edit", methods={"POST"}, name="content_edit_submit")
     */
    public function editSubmit(string $id): RedirectResponse
    {
        return $this->redirectToRoute('content_edit');
    }
}
