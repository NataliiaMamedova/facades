<?php
declare(strict_types=1);

namespace DpDocument\Facades\CDN;

use DpDocument\Facades\CDN\DTO\FileData;
use DpDocument\Facades\CDN\DTO\FileUpload;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class CDNFacade
 *
 * @package DpDocument\Facades\CDN
 * @since   1.3.0
 * DpDocument | Research & Development
 */
final class CDNFacade
{
    const TYPE_PRIVATE = 1;
    const TYPE_PUBLIC = 0;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * BuildingsFacade constructor.
     *
     * @param ClientInterface $client
     * @param string $baseUrl
     */
    public function __construct(ClientInterface $client, string $baseUrl)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $accessToken
     * @param array $file
     * @param int $directoryType
     *
     * @return FileUpload
     * @throws CDNException
     * @since   1.3.0
     */
    public function uploadFile(string $accessToken, array $file, int $directoryType): FileUpload
    {
        try {
            $url = $this->baseUrl . '/upload';
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken
                        ],
                        'multipart' => [
                            [
                                'name' => 'file',
                                'contents' => \fopen($file['path'], 'r'),
                                'filename' => $file['filename']
                            ],
                            [
                                'name' => 'private',
                                'contents' => $directoryType
                            ]
                        ]
                    ]);
            $data = $this->processResult($response);

            return FileUpload::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new CDNException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param array $files
     * @param int $directoryType
     *
     * @return FileUpload[]|[]
     * @throws CDNException
     * @since   1.3.0
     */
    public function uploadMultipleFiles(string $accessToken, array $files, int $directoryType): array
    {
        $data = [];

        foreach ($files as $file) {
            $data[] = [
                'name' => 'file[]',
                'contents' => \fopen($file['path'], 'r'),
                'filename' => $file['filename']
            ];
        }

        $data[] = [
            'name' => 'private',
            'contents' => $directoryType
        ];

        try {
            $url = $this->baseUrl . '/upload';
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken
                        ],
                        'multipart' => $data
                    ]);
            $data = $this->processResult($response);
            $filesData = [];

            foreach ($data as $item) {
                $filesData[] = FileUpload::createFromResponse($item);
            }

            return $filesData;
        } catch (\Throwable $throwable) {
            throw new CDNException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param array $parameters
     * @param string $path
     *
     * @return string
     * @throws CDNException
     * @since   1.3.0
     */
    public function getImageThumbnail(string $accessToken, array $parameters, string $path): string
    {
        try {
            $url = $this->baseUrl . '/thumb/' . $parameters['width'] . 'x' . $parameters['height'] . '/' . $path;
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return $url;
        } catch (\Throwable $throwable) {
            throw new CDNException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param null|string $search
     * @param int|null $page
     * @param int|null $limit
     * @param array|null $sort
     *
     * @return FileData[]|[]
     * @throws CDNException
     * @since   1.3.0
     */
    public function searchFiles(
        string $accessToken,
        ?string $search = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $sort = null
    ): array {
        $queryParams = [];

        if (null !== $search) {
            $queryParams['q'] = $search;
        }

        if (null !== $page) {
            $queryParams['page'] = $page;
        }

        if (null !== $limit) {
            $queryParams['limit'] = $limit;
        }

        if (null !== $sort) {
            $queryParams['sort'] = \join(',', \array_unique($sort));
        }

        try {
            $url = $this->baseUrl . '/search';
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'query' => $queryParams
                    ]);
            $data = $this->processResult($response);

            $files = [];

            foreach ($data as $item) {
                $files[] = FileData::createFromResponse($item);
            }

            return $files;
        } catch (\Throwable $throwable) {
            throw new CDNException($throwable->getMessage(), $throwable->getCode());
        }

    }

    /**
     * @param string $accessToken
     * @param string $id
     *
     * @return bool
     * @throws CDNException
     * @since   1.3.0
     */
    public function deleteFile(string $accessToken, string $id): bool
    {
        try {
            $url = $this->baseUrl . '/delete/' . $id;
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new CDNException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array|null
     * @throws \DpDocument\Facades\CDN\CDNException
     */
    private function processResult(ResponseInterface $response): ?array
    {
        if (in_array($response->getStatusCode(), [200, 204])) {
            return $this->parseBody($response->getBody()->getContents());
        } else {
            $error = $this->parseBody($response->getBody()->getContents());

            if (isset($error['error'])) {
                throw new CDNException($error['error']);
            } elseif (isset($error['critical'])) {
                throw new CDNException($error['critical']);
            } else {
                throw new CDNException('Internal server error');
            }
        }
    }

    /**
     * @param string $json
     *
     * @return array|null
     */
    private function parseBody(string $json): ?array
    {
        $data = \json_decode($json, true);

        if (JSON_ERROR_NONE !== \json_last_error() || !is_array($data)) {
            return null;
        }

        return $data;
    }
}