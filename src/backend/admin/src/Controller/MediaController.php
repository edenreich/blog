<?php

namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends NavigationAwareController
{
    /**
     * @Route("/media", methods={"GET"}, name="media")
     */
    public function index(): Response
    {
        $client = new Client([
            'base_uri' => $this->getParameter('api_url'),
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $this->getAuthToken()),
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $response = $client->get('/api/v1/media/images');
            $body = json_decode($response->getBody(), true);

            return $this->render('media/index.html.twig', [
                'images' => $body['files'],
            ]);
        } catch (ClientExceptionInterface $exception) {
            $this->addFlash(
                'danger',
                'Could not fetch the images!'
            );

            return $this->render('media/index.html.twig');
        }
    }

    /**
     * @Route("/media/images/upload", methods={"POST"}, name="media_images_upload")
     */
    public function uploadImageAction(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $content = json_decode($request->getContent(), true);
            $blobBase64 = $content['upload'];
        } else {
            $file = $request->files->get('upload');
            $handler = fopen($file->getRealPath(), 'rb');
            $blob = stream_get_contents($handler);
            $blobBase64 = base64_encode($blob);
            fclose($handler);
        }

        $client = new Client([
            'base_uri' => $this->getParameter('api_url'),
            RequestOptions::HEADERS => [
                'Authorization' => sprintf('Bearer %s', $this->getAuthToken()),
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $response = $client->post('/api/v1/media/images/upload', [
                RequestOptions::JSON => [
                    'upload' => $blobBase64,
                ],
            ]);
            $body = json_decode($response->getBody(), true);

            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'success' => true,
                    'url' => $body['file']['mediaLink'],
                ]);
            } else {
                $this->addFlash(
                    'success',
                    'Image successfully uploaded!'
                );

                return $this->redirectToRoute('media');
            }
        } catch (ClientExceptionInterface $exception) {
            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ]);
            } else {
                $this->addFlash(
                    'danger',
                    'Could not upload the image!'
                );

                return $this->redirectToRoute('media');
            }
        }
    }

    /**
     * Fetch the JWT.
     *
     * @throws ClientExceptionInterface
     */
    private function getAuthToken(): string
    {
        $client = new Client([
            'base_uri' => $this->getParameter('api_url'),
        ]);
        $response = $client->post('api/authorize', [
            RequestOptions::JSON => [
                'username' => 'admin@gmail.com',
                'password' => 'admin',
            ],
        ]);

        return json_decode($response->getBody(), true)['token'];
    }
}
