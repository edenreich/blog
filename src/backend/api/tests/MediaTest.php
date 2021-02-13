<?php

namespace App\Tests;

use Google\Cloud\Storage\Bucket;
use Google\Cloud\Storage\StorageClient;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class MediaTest extends AbstractTestCase
{
    /**
     * Store a Google Cloud Bucket client.
     */
    private Bucket $bucket;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var StorageClient */
        $storage = self::$container->get(StorageClient::class);
        $this->bucket = $storage->bucket('eden-reich-com-assets');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->bucket);
    }

    public function testAnImageIsUploadedToGoogleBucketAndIsAccessibleOnTheInternet(): void
    {
        $file = file_get_contents(__DIR__.'/fixtures/test.png');
        $base64Blob = base64_encode($file);

        $response = $this->client->post('media/images/upload', [
            RequestOptions::JSON => [
                'upload' => $base64Blob,
            ],
        ]);
        $body = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('size', $body['file']);
        $this->assertArrayHasKey('contentType', $body['file']);
        $this->assertArrayHasKey('bucket', $body['file']);
        $this->assertArrayHasKey('name', $body['file']);
        $this->assertArrayHasKey('mediaLink', $body['file']);
        $this->assertArrayHasKey('name', $body['file']);
        $this->assertEquals('eden-reich-com-assets', $body['file']['bucket']);
        $this->assertEquals('8382', $body['file']['size']);
        $this->assertNotEquals('test.png', $body['file']['name']);

        $client = new Client();
        $response = $client->get($body['file']['mediaLink']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Content-Length'));
        $this->assertEquals('8382', $response->getHeader('Content-Length')[0]);
        $this->assertTrue($response->hasHeader('Content-Type'));
        $this->assertEquals('image/png', $response->getHeader('Content-Type')[0]);

        $object = $this->bucket->object($body['file']['name']);
        $object->delete();
    }
}
