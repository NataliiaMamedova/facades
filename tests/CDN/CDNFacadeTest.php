<?php
declare(strict_types=1);

namespace DpDocument\Adapters\Tests\CDN;

use DpDocument\Facades\CDN\CDNFacade;
use DpDocument\Facades\CDN\DTO\FileData;
use DpDocument\Facades\CDN\DTO\FileUpload;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class CDNFacadeTest
 *
 * @package DpDocument\Adapters\Tests\CDN
 * DpDocument | Research & Development
 */
class CDNFacadeTest extends TestCase
{
    /**
     * @var string
     */
    private static $baseApiUrl = 'http://some.host/api';

    public function testUploadFile()
    {
        $token = 'some-token';
        $data = [
            'uuid' => 'f047884d-7681-4470-80b3-290b85dd6f9a',
            'href' => 'http://localhost/public/e8/6a/e86a78b9a8cd35ca85922a7304b0a563.pdf',
            'type' => 'document',
            'size' => 9227289
        ];

        $fileArray = [
            'filename' => 'someTextFile.txt',
            'path' => './README.md'
        ];

        $client = $this->createClient($data, 200);
        $cdnFacade = new CDNFacade($client, self::$baseApiUrl);
        $file = $cdnFacade->uploadFile($token, $fileArray, CDNFacade::TYPE_PUBLIC);
        $this->assertInstanceOf(FileUpload::class, $file);
        $this->assertEquals($data['uuid'], $file->uuid);
        $this->assertEquals($data['href'], $file->href);
        $this->assertEquals($data['type'], $file->type);
        $this->assertEquals($data['size'], $file->size);
    }

    public function testUploadMultipleFiles()
    {
        $token = 'some-token';
        $data = [
            [
                'uuid' => 'f047884d-7681-4470-80b3-290b85dd6f9a',
                'href' => 'http://localhost/public/e8/6a/e86a78b9a8cd35ca85922a7304b0a563.pdf',
                'type' => 'document',
                'size' => 9227289
            ],
            [
                'uuid' => 'f047884d-7681-4470-80b3-290b85dd6f9a',
                'href' => 'http://localhost/public/e8/6a/e86a78b9a8cd35ca85922a7304b0a563.pdf',
                'type' => 'document',
                'size' => 9227289
            ]
        ];

        $fileArray = [
            [
                'filename' => 'someTextFile.txt',
                'path' => './README.md'
            ],
            [
                'filename' => 'someTextFile.txt',
                'path' => './README.md'
            ]
        ];

        $client = $this->createClient($data, 200);
        $cdnFacade = new CDNFacade($client, self::$baseApiUrl);
        $files = $cdnFacade->uploadMultipleFiles($token, $fileArray, CDNFacade::TYPE_PUBLIC);
        $this->assertContainsOnlyInstancesOf(FileUpload::class, $files);
        $this->assertEquals($data[0]['uuid'], $files[0]->uuid);
        $this->assertEquals($data[0]['href'], $files[0]->href);
        $this->assertEquals($data[0]['type'], $files[0]->type);
        $this->assertEquals($data[0]['size'], $files[0]->size);
        $this->assertEquals($data[1]['uuid'], $files[1]->uuid);
        $this->assertEquals($data[1]['href'], $files[1]->href);
        $this->assertEquals($data[1]['type'], $files[1]->type);
        $this->assertEquals($data[1]['size'], $files[1]->size);
    }

    public function testGetImageThumbnail()
    {
        $token = 'some-token';
        $data = [
            'path' => 'http://localhost/public/e8/6a/e86a78b9a8cd35ca85922a7304b0a563.jpg',
            'width' => 300,
            'height' => 300
        ];

        $fileString = 'public/e8/6a/e86a78b9a8cd35ca85922a7304b0a563.jpg';

        $client = $this->createClient(null, 200);
        $cdnFacade = new CDNFacade($client, self::$baseApiUrl);
        $file = $cdnFacade->getImageThumbnail($token, $data, $fileString);
        $this->assertContains($fileString, $file);
    }

    public function testSearchFiles()
    {
        $token = 'some-token';
        $data = [
            [
                'uuid' => '820e694a-77ae-4c73-b4ae-d6f3958c95eb',
                'file' => 'd99e84ac6238c8a1e4e77d69bfdf0732.pdf',
                'type' => 'application/pdf',
                'path' => 'public/d9/9e/d99e84ac6238c8a1e4e77d69bfdf0732.pdf',
                'href' => 'http://localhost/public/d9/9e/d99e84ac6238c8a1e4e77d69bfdf0732.pdf',
                'size' => 9227289,
                'uploaded_at' => '2018-02-26T14:43:06+02:00',
                'original_file_name' => '9781786468949-GO_PROGRAMMING_BLUEPRINTS_SECOND_EDITION.pdf',
                'uploaded_by' => 'admin'
            ],
            [
                'uuid' => 'abaa142f-5ed2-48f8-bf9a-59f065783845',
                'file' => '317fbc4a2499b233de7b06bdca75e046.jpg',
                'type' => 'image/jpeg',
                'path' => 'public/31/7f/317fbc4a2499b233de7b06bdca75e046.jpg',
                'href' => 'http://localhost/public/31/7f/317fbc4a2499b233de7b06bdca75e046.jpg',
                'size' => 166,
                'uploaded_at' => '2018-02-26T14:42:09+02:00',
                'original_file_name' => 'test.jpg',
                'uploaded_by' => 'admin'
            ]
        ];

        $client = $this->createClient($data, 200);
        $cdnFacade = new CDNFacade($client, self::$baseApiUrl);
        $housings = $cdnFacade->searchFiles($token);
        $this->assertContainsOnlyInstancesOf(FileData::class, $housings);
    }

    public function testDeleteFile()
    {
        $token = 'test-token';

        $client       = $this->createClient(null, 200);
        $cdnFacade  = new CDNFacade($client, self::$baseApiUrl);
        $updateResult = $cdnFacade->deleteFile($token, 'test');
        $this->assertTrue($updateResult);
    }

    /**
     * @param array $responseBody
     * @param int   $statusCode
     *
     * @return \GuzzleHttp\Client
     */
    private function createClient(?array $responseBody, int $statusCode): Client
    {
        $mock = new MockHandler(
            [
                new Response($statusCode, ['Content-Type' => 'application/json'],
                    $responseBody ? \json_encode($responseBody) : $responseBody),
            ]
        );

        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}