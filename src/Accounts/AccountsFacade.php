<?php
declare(strict_types=1);

namespace DpDocument\Facades\Accounts;

use DpDocument\Facades\Accounts\DTO\TokenData;
use GuzzleHttp\ClientInterface;

/**
 * Class AccountsFacade
 *
 * @package DpDocument\Facades\Accounts
 * @since   1.0.0
 * DpDocument|Research & Development
 */
final class AccountsFacade
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $tokenUrl;

    /**
     * AccountsFacade constructor.
     *
     * @param \GuzzleHttp\ClientInterface $client
     * @param string                      $tokenUrl
     */
    public function __construct(ClientInterface $client, string $tokenUrl)
    {
        $this->client   = $client;
        $this->tokenUrl = $tokenUrl;
    }

    /**
     * @param string $appId
     * @param string $appSecret
     *
     * @return \DpDocument\Facades\Accounts\DTO\TokenData|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.0.0
     */
    public function getAppAccessToken(string $appId, string $appSecret): ?TokenData
    {
        try {
            $response = $this->client->request('post', $this->tokenUrl, [
                'auth'        => [$appId, $appSecret],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);

            if (200 === $response->getStatusCode()) {
                return TokenData::createFromJson($response->getBody()->getContents());
            }
        } catch (\Throwable $throwable) {
            // DoNothing
        }

        return null;
    }

    /**
     * @param string $appId
     * @param string $appSecret
     * @param string $username
     * @param string $password
     *
     * @return \DpDocument\Facades\Accounts\DTO\TokenData|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.0.0
     */
    public function getUserAccessToken(string $appId, string $appSecret, string $username, string $password): ?TokenData
    {
        try {
            $response = $this->client->request('post', $this->tokenUrl, [
                'auth'        => [$appId, $appSecret],
                'form_params' => [
                    'grant_type' => 'password',
                    'username'   => $username,
                    'password'   => $password
                ]
            ]);

            if (200 === $response->getStatusCode()) {
                return TokenData::createFromJson($response->getBody()->getContents());
            }
        } catch (\Throwable $throwable) {
            // DoNothing
        }

        return null;
    }

    /**
     * @param string $appId
     * @param string $appSecret
     * @param string $refreshToken
     *
     * @return \DpDocument\Facades\Accounts\DTO\TokenData|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.0.0
     */
    public function refreshTokenBy(string $appId, string $appSecret, string $refreshToken): ?TokenData
    {
        try {
            $response = $this->client->request('post', $this->tokenUrl, [
                'auth'        => [$appId, $appSecret],
                'form_params' => [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $refreshToken
                ]
            ]);

            if (200 === $response->getStatusCode()) {
                return TokenData::createFromJson($response->getBody()->getContents());
            }
        } catch (\Throwable $throwable) {
            // DoNothing
        }

        return null;
    }
}
