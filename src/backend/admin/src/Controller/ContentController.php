<?php

namespace App\Controller;

use App\Dto\Article;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends NavigationAwareController
{
    /**
     * @Route("/content", methods={"GET"}, name="content")
     */
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('content_list');
    }

    /**
     * @Route("/content/list", methods={"GET"}, name="content_list")
     */
    public function list(): Response
    {
        if ($this->request->isXmlHttpRequest()) {
            $client = new Client([
                'base_uri' => $this->getParameter('api_url'),
                RequestOptions::HEADERS => [
                    'Authorization' => sprintf('Bearer %s', $this->getAuthToken()),
                    'Content-Type' => 'application/json',
                ],
            ]);
            $response = $client->get('/api/v1/articles');
            $responseArticles = json_decode($response->getBody(), true);
            $articles = array_map(function ($responseArticle) {
                return new Article($responseArticle);
            }, $responseArticles);

            return $this->json($articles);
        }

        return $this->render('content/list.html.twig');
    }

    /**
     * @Route("/content/create", methods={"GET"}, name="content_create")
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
        $client = new Client([
            'base_uri' => $this->getParameter('api_url'),
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $this->getAuthToken()),
                'Content-Type' => 'application/json',
            ],
        ]);
        $response = $client->get(sprintf('/api/v1/articles/%s', $id));
        $responseArticle = json_decode($response->getBody(), true);
        $article = new Article($responseArticle);

        return $this->render('content/edit.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/content/{id}/edit", methods={"POST"}, name="content_edit_submit")
     */
    public function editAction(string $id, Request $request): RedirectResponse
    {
        $payload = $request->request->all();
        $article = new Article($payload);

        if (!empty($payload['publish'])) {
            $article->setPublishedAt(new DateTime());
        } else {
            $article->setPublishedAt(null);
        }

        $client = new Client([
            'base_uri' => $this->getParameter('api_url'),
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $this->getAuthToken()),
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $client->put(sprintf('/api/v1/articles/%s', $id), [
                RequestOptions::JSON => $article,
            ]);
            $this->addFlash(
                'success',
                sprintf('Article %s has been successfully saved!', $payload['title'])
            );

            return $this->redirectToRoute('content_edit_submit', [ 'id' => $id ]);
        } catch (ClientException $exception) {
            $this->addFlash(
                'danger',
                'Could not save the article!'
            );

            return $this->redirectToRoute('content_edit');
        }
    }

    /**
     * @Route("/content/create", methods={"POST"}, name="content_create_submit")
     */
    public function createAction(Request $request): RedirectResponse
    {
        $payload = $request->request->all();
        $article = new Article($payload);

        $session = $request->getSession();
        foreach ($payload as $key => $value) {
            $session->set('article.'.$key, $value);
        }

        $client = new Client([
            'base_uri' => $this->getParameter('api_url'),
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $this->getAuthToken()),
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $client->post('/api/v1/articles', [
                RequestOptions::JSON => $article,
            ]);
            $this->addFlash(
                'success',
                sprintf('Article %s has been successfully created!', $article->getTitle())
            );

            return $this->redirectToRoute('content_list');
        } catch (ClientException $exception) {
            $this->addFlash(
                'danger',
                'Could not create the article!'
            );

            return $this->redirectToRoute('content_create');
        }
    }

    /**
     * @Route("/content/{id}/delete", methods={"GET"}, name="content_delete")
     */
    public function deleteAction(string $id): Response
    {
        $client = new Client([
            'base_uri' => $this->getParameter('api_url'),
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $this->getAuthToken()),
                'Content-Type' => 'application/json',
            ],
        ]);

        $client->delete(sprintf('/api/v1/articles/%s', $id));
        $this->addFlash(
            'success',
            'Article has been successfully deleted!'
        );

        return $this->redirectToRoute('content_list');
    }

    /**
     * Fetch the JWT.
     */
    private function getAuthToken(): string
    {
        $client = new Client([
            'base_uri' => $this->getParameter('api_url'),
        ]);
        try {
            $response = $client->post('api/authorize', [
                RequestOptions::JSON => [
                    'username' => 'admin@gmail.com',
                    'password' => 'admin',
                ],
            ]);

            return json_decode($response->getBody(), true)['token'];
        } catch (ClientExceptionInterface $exception) {
            $this->addFlash(
                'danger',
                'Could not fetch auth token!'
            );

            return $this->redirectToRoute('content_list');
        }
    }
}
