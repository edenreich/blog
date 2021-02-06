<?php

namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/media/images/upload", methods={"POST"}, name="media_images_upload")
     */
    public function uploadImageAction(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $blobBase64 = $body['upload'];

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
            $body = json_encode($response->getBody(), true);

            return $this->json([
                'success' => true,
                'url' => $body['url'],
            ]);
        } catch (ClientExceptionInterface $exception) {
            return $this->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
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
