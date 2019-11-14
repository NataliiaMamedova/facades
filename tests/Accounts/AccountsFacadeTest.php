<?php
declare(strict_types=1);

namespace DpDocument\Adapters\Tests;

use DpDocument\Facades\Accounts\AccountsFacade;
use DpDocument\Facades\Accounts\DTO\TokenData;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class AccountsFacadeTest
 *
 * @package DpDocument\Adapters\Tests
 * DpDocument | Research & Development
 */
class AccountsFacadeTest extends TestCase
{
    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAppAccessToken(): void
    {
        $inputTokenData = $this->getTokenStubs(false);

        $client  = $this->createClient($inputTokenData);
        $adapter = new AccountsFacade($client, 'http://localhost');
        /** @var TokenData $outputTokenData */
        $outputTokenData = $adapter->getAppAccessToken('app_id', 'app_secret');
        $this->assertTokenData($inputTokenData, $outputTokenData);
        $failedResponse = $adapter->getAppAccessToken('app_id', 'app_secret');
        $this->assertEmpty($failedResponse);
    }

    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetUserAccessToken(): void
    {
        $inputTokenData = $this->getTokenStubs();

        $client  = $this->createClient($inputTokenData);
        $adapter = new AccountsFacade($client, 'http://localhost');
        /** @var TokenData $outputTokenData */
        $outputTokenData = $adapter->getUserAccessToken('app_id', 'app_secret', 'test_username', 'test_password');
        $this->assertTokenData($inputTokenData, $outputTokenData);
        $failedResponse = $adapter->getAppAccessToken('app_id', 'app_secret');
        $this->assertEmpty($failedResponse);
    }

    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRefreshTokenBy(): void
    {
        $inputTokenData = $this->getTokenStubs();

        $client = $this->createClient($inputTokenData);
        $facade = new AccountsFacade($client, 'http://localhost');
        /** @var \DpDocument\Facades\Accounts\DTO\TokenData $outputTokenData */
        $outputTokenData = $facade->refreshTokenBy('app_id', 'app_secret', 'refresh_token');
        $this->assertInstanceOf(TokenData::class, $outputTokenData);
        $failedResponse = $facade->refreshTokenBy('app_id', 'app_secret', 'refresh_token');
        $this->assertEmpty($failedResponse);
    }

    /**
     * @param array                                             $expect
     * @param \DpDocument\Facades\Accounts\DTO\TokenData|null $actual
     *
     * @throws \Exception
     */
    private function assertTokenData(array $expect, ?TokenData $actual): void
    {
        $this->assertInstanceOf(TokenData::class, $actual);
        $this->assertEquals($expect['access_token'], $actual->accessToken);
        $this->assertEquals($expect['token_type'], $actual->tokenType);
        $this->assertEquals($expect['expires_in'], $actual->expiresIn);
        $this->assertEquals($expect['refresh_token'], $actual->refreshToken);
    }

    /**
     * @param array $responseBody
     *
     * @return \GuzzleHttp\Client
     */
    private function createClient(array $responseBody): Client
    {
        $mock = new MockHandler(
            [
                new Response(200, ['Content-Type' => 'application/json'], \json_encode($responseBody)),
                new Response(400, ['Content-Type' => 'application/json'], 'error')
            ]
        );

        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }

    /**
     * @param bool $withRefreshToken
     *
     * @return array
     */
    private function getTokenStubs(bool $withRefreshToken = true): array
    {
        return $withRefreshToken
            ? [
                'access_token'  => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJqdGkiOiI0ZGFkZjYxNi03MzFmLTRjZjMtYmE3Yy1kZjcyYjhiMGIyNmYiLCJ1aWQiOiJlMzMzZGIyOC1jZDBmLTQ2NGEtYmUzNi01NDBjMzdmMjAxYTYiLCJ1c3IiOiJhZG1pbiIsImlhdCI6IjE1MTc4MjczODYiLCJleHAiOiIxNTE3ODI5MTg2Iiwicm9sZXMiOlsiUk9MRV9BQ0NPVU5UU19ST09UIiwiUk9MRV9BQ0NPVU5UU19BRE1JTiIsIlJPTEVfQ0ROX1JPT1QiLCJST0xFX05PVElGSUNBVElPTlNfUk9PVCIsIlJPTEVfTU9ORVlfUk9PVCIsIlJPTEVfQlVJTERJTkdTX1JPT1QiLCJST0xFX0FDQ09VTlRTX1VTRVIiXX0.XDvJwFfMG1_-hZpKpV3s6GZSF1PSKqgM_5S2MrHoUM-H0rYr1MB_BDUFUpB9UhgFakTaKNOM978j6d2cMVXttlTWCGn_71O4blPc1id7ypzpLiyJbw7V29jzje1Bw97HNG_IweP8-kK-xK4bqTlhwM0m1AfvBsbJkkSuQvNyTSE7iHOnbeu_zDN-pVu4FsZsc8ZChctBPAI6jhXK4ZDH3LdoKyhdq8Vczt2s-YD5qDpzRhu-0yXvpeKRVgvoT5RQ4lbNXk2IeRaJmUbDjycelGMqNJHJkYRYpKKYfevO0dGFvDCcmwtuKqLfOk5ZLTsmnYT4r4n58YDdyc8aWeFwIQ',
                'token_type'    => 'Bearer',
                'expires_in'    => 1800,
                'refresh_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJqdGkiOiI0ZGFkZjYxNi03MzFmLTRjZjMtYmE3Yy1kZjcyYjhiMGIyNmYiLCJ1aWQiOiJlMzMzZGIyOC1jZDBmLTQ2NGEtYmUzNi01NDBjMzdmMjAxYTYiLCJ1c3IiOiJhZG1pbiIsImlhdCI6IjE1MTc4MjczODYiLCJleHAiOiIxNTE3ODI5MTg2Iiwicm9sZXMiOlsiUk9MRV9BQ0NPVU5UU19ST09UIiwiUk9MRV9BQ0NPVU5UU19BRE1JTiIsIlJPTEVfQ0ROX1JPT1QiLCJST0xFX05PVElGSUNBVElPTlNfUk9PVCIsIlJPTEVfTU9ORVlfUk9PVCIsIlJPTEVfQlVJTERJTkdTX1JPT1QiLCJST0xFX0FDQ09VTlRTX1VTRVIiXX0.XDvJwFfMG1_-hZpKpV3s6GZSF1PSKqgM_5S2MrHoUM-H0rYr1MB_BDUFUpB9UhgFakTaKNOM978j6d2cMVXttlTWCGn_71O4blPc1id7ypzpLiyJbw7V29jzje1Bw97HNG_IweP8-kK-xK4bqTlhwM0m1AfvBsbJkkSuQvNyTSE7iHOnbeu_zDN-pVu4FsZsc8ZChctBPAI6jhXK4ZDH3LdoKyhdq8Vczt2s-YD5qDpzRhu-0yXvpeKRVgvoT5RQ4lbNXk2IeRaJmUbDjycelGMqNJHJkYRYpKKYfevO0dGFvDCcmwtuKqLfOk5ZLTsmnYT4r4n58YDdyc8aWeFwIQ'
            ]
            : [
                'access_token'  => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJqdGkiOiI0ZGFkZjYxNi03MzFmLTRjZjMtYmE3Yy1kZjcyYjhiMGIyNmYiLCJ1aWQiOiJlMzMzZGIyOC1jZDBmLTQ2NGEtYmUzNi01NDBjMzdmMjAxYTYiLCJ1c3IiOiJhZG1pbiIsImlhdCI6IjE1MTc4MjczODYiLCJleHAiOiIxNTE3ODI5MTg2Iiwicm9sZXMiOlsiUk9MRV9BQ0NPVU5UU19ST09UIiwiUk9MRV9BQ0NPVU5UU19BRE1JTiIsIlJPTEVfQ0ROX1JPT1QiLCJST0xFX05PVElGSUNBVElPTlNfUk9PVCIsIlJPTEVfTU9ORVlfUk9PVCIsIlJPTEVfQlVJTERJTkdTX1JPT1QiLCJST0xFX0FDQ09VTlRTX1VTRVIiXX0.XDvJwFfMG1_-hZpKpV3s6GZSF1PSKqgM_5S2MrHoUM-H0rYr1MB_BDUFUpB9UhgFakTaKNOM978j6d2cMVXttlTWCGn_71O4blPc1id7ypzpLiyJbw7V29jzje1Bw97HNG_IweP8-kK-xK4bqTlhwM0m1AfvBsbJkkSuQvNyTSE7iHOnbeu_zDN-pVu4FsZsc8ZChctBPAI6jhXK4ZDH3LdoKyhdq8Vczt2s-YD5qDpzRhu-0yXvpeKRVgvoT5RQ4lbNXk2IeRaJmUbDjycelGMqNJHJkYRYpKKYfevO0dGFvDCcmwtuKqLfOk5ZLTsmnYT4r4n58YDdyc8aWeFwIQ',
                'token_type'    => 'Bearer',
                'expires_in'    => 1800,
                'refresh_token' => ''
            ];
    }
}
