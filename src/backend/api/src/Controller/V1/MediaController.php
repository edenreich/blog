<?php

namespace App\Controller\V1;

use Google\Cloud\Storage\StorageClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

class MediaController extends AbstractController
{
    /**
     * @Route("/media/images/upload", methods={"POST"}, name="media.images.upload")
     */
    public function uploadImageAction(Request $request, StorageClient $storage): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $blob = base64_decode($body['upload']);
        $tempFile = fopen('php://temp', 'rw');
        fputs($tempFile, $blob);
        rewind($tempFile);
        $mime = mime_content_type($tempFile);
        $extension = 'txt';
        switch ($mime) {
            case 'image/png':
                $extension = 'png';
            break;
            case 'image/jpg':
            case 'image/jpeg':
                $extension = 'jpg';
            break;
        }

        $fileName = new UuidV4().'.'.$extension;
        $bucket = $storage->bucket('eden-reich-com-assets');
        $object = $bucket->upload($tempFile, [
            'name' => $fileName,
        ]);

        return $this->json(['file' => $object->info()]);
    }
}
