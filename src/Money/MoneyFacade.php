<?php
declare(strict_types=1);

namespace DpDocument\Facades\Money;

use DpDocument\Facades\Money\DTO\ActiveRate;
use DpDocument\Facades\Money\DTO\ConverterResult;
use DpDocument\Facades\Money\DTO\Rate;
use DpDocument\Facades\Money\DTO\Subscriber;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MoneyFacade
 *
 * @package DpDocument\Facades\Money
 * @since   1.1.0
 * DpDocument | Research & Development
 */
final class MoneyFacade
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * MoneyFacade constructor.
     *
     * @param \GuzzleHttp\ClientInterface $client
     * @param string                      $baseUrl
     */
    public function __construct(ClientInterface $client, string $baseUrl)
    {
        $this->client  = $client;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Get currecnies list
     *
     * @return array
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function getCurrenciesList(): array
    {
        try {
            $url      = $this->baseUrl . '/currencies';
            $response = $this->client->request('get', $url);

            return $this->processResult($response);
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Currency converter
     *
     * @param int|float $amount         Amount
     * @param string    $inputCurrency  Input currency
     * @param string    $outputCurrency Output currency
     *
     * @return \DpDocument\Facades\Money\DTO\ConverterResult
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function convertCurrency($amount, string $inputCurrency, string $outputCurrency): ConverterResult
    {
        if (!is_numeric($amount)) {
            throw new MoneyException('Amount must be numeric value (int of float)');
        }

        $this->currencyValidator($inputCurrency, $outputCurrency);

        try {
            $url      = $this->baseUrl . '/converter/' . $amount . '/' . $inputCurrency . '/' . $outputCurrency;
            $response = $this->client->request('get', $url);
            $data     = $this->processResult($response);

            return new ConverterResult($data['amount'], $data['currency']);
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Create new active rate
     *
     * @param string $baseCurrency
     * @param string $counterCurrency
     * @param string $accessToken
     *
     * @return \DpDocument\Facades\Money\DTO\ActiveRate
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function newActiveRate(string $baseCurrency, string $counterCurrency, string $accessToken): ActiveRate
    {
        $this->currencyValidator($baseCurrency, $counterCurrency);

        try {
            $url      = $this->baseUrl . '/rates/active';
            $response = $this->client
                ->request('post',
                          $url,
                          [
                              'headers' => [
                                  'Authorization' => 'Bearer ' . $accessToken,
                                  'Content-Type'  => 'application/json'
                              ],
                              'json'    => ['base_currency' => $baseCurrency, 'counter_currency' => $counterCurrency]
                          ]);
            $data     = $this->processResult($response);

            return ActiveRate::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param int    $id
     * @param string $accessToken
     *
     * @return bool
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function deleteActiveRate(int $id, string $accessToken): bool
    {
        try {
            $url      = $this->baseUrl . '/rates/active/' . $id;
            $response = $this->client
                ->request('delete',
                          $url,
                          [
                              'headers' => [
                                  'Authorization' => 'Bearer ' . $accessToken,
                                  'Content-Type'  => 'application/json'
                              ],
                          ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Return all active rates
     *
     * @return ActiveRate[]
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function getAllActiveRates(): array
    {
        try {
            $url      = $this->baseUrl . '/rates/active';
            $response = $this->client
                ->request('get',
                          $url,
                          [
                              'headers' => [
                                  'Content-Type' => 'application/json'
                              ],
                          ]);
            $data     = $this->processResult($response);

            $activeRates = [];

            foreach ($data as $item) {
                $activeRates[] = ActiveRate::createFromResponse($item);
            }

            return $activeRates;
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Return rates for today
     *
     * @return Rate[]
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function getRatesForToday(): array
    {
        return $this->getRatesFor('today');
    }

    /**
     * Return rates for last 7 days
     *
     * @return Rate[]
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function getRatesForWeek(): array
    {
        return $this->getRatesFor('week');
    }

    /**
     * Return rates for this month
     *
     * @return Rate[]
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function getRatesForMonth(): array
    {
        return $this->getRatesFor('month');
    }

    /**
     * Return rates for this year
     *
     * @return Rate[]
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function getRatesForYear(): array
    {
        return $this->getRatesFor('year');
    }

    /**
     * Get rates for period
     *
     * @param string $period
     *
     * @return Rate[]
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function getRatesFor(string $period): array
    {
        try {
            $url      = $this->baseUrl . '/rates/' . $period;
            $response = $this->client
                ->request('get',
                          $url,
                          [
                              'headers' => [
                                  'Content-Type' => 'application/json'
                              ],
                          ]);
            $data     = $this->processResult($response);

            $activeRates = [];

            foreach ($data as $item) {
                $activeRates[] = Rate::createFromResponse($item);
            }

            return $activeRates;
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Create new subscriber
     *
     * @param string      $email        Subscriber email
     * @param null|string $mobileNumber Mobile phone number
     * @param string      $accessToken  Access token
     *
     * @return \DpDocument\Facades\Money\DTO\Subscriber
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function newSubscriber(string $email, ?string $mobileNumber = null, string $accessToken): Subscriber
    {
        try {
            $url      = $this->baseUrl . '/subscribers';
            $response = $this->client
                ->request('post',
                          $url,
                          [
                              'headers' => [
                                  'Authorization' => 'Bearer ' . $accessToken,
                                  'Content-Type'  => 'application/json'
                              ],
                              'json'    => ['email' => $email, 'mobile_number' => $mobileNumber]
                          ]);
            $data     = $this->processResult($response);

            return Subscriber::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Update subscriber
     *
     * @param int         $id           Subscriber ID
     * @param string      $email        Email
     * @param null|string $mobileNumber Mobile phone number
     * @param string      $accessToken  Access Token
     *
     * @return bool
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function updateSubscriber(
        int $id,
        ?string $email = null,
        ?string $mobileNumber = null,
        string $accessToken
    ): bool {
        $jsonData = [];

        if (null !== $email) {
            $jsonData['email'] = $email;
        }

        if (null !== $mobileNumber) {
            $jsonData['mobile_number'] = $mobileNumber;
        }

        try {
            $url      = $this->baseUrl . '/subscribers/' . $id;
            $response = $this->client
                ->request('put',
                          $url,
                          [
                              'headers' => [
                                  'Authorization' => 'Bearer ' . $accessToken,
                                  'Content-Type'  => 'application/json'
                              ],
                              'json'    => $jsonData
                          ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Delete subsciber
     *
     * @param int    $id          Subscriber ID
     * @param string $accessToken Access token
     *
     * @return bool
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function deleteSubscriber(int $id, string $accessToken): bool
    {
        try {
            $url      = $this->baseUrl . '/subscribers/' . $id;
            $response = $this->client
                ->request('delete',
                          $url,
                          [
                              'headers' => [
                                  'Authorization' => 'Bearer ' . $accessToken,
                                  'Content-Type'  => 'application/json'
                              ]
                          ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string      $accessToken
     * @param null|string $search
     * @param int|null    $page
     * @param int|null    $limit
     * @param array|null  $sort
     *
     * @return Subscriber[]
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @since 1.1.0
     */
    public function getSubscribers(
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
            $url      = $this->baseUrl . '/subscribers';
            $response = $this->client
                ->request('get',
                          $url,
                          [
                              'headers' => [
                                  'Authorization' => 'Bearer ' . $accessToken,
                                  'Content-Type'  => 'application/json'
                              ],
                              'query'   => $queryParams
                          ]);
            $data     = $this->processResult($response);

            $subscribers = [];

            foreach ($data as $item) {
                $subscribers[] = Subscriber::createFromResponse($item);
            }

            return $subscribers;
        } catch (\Throwable $throwable) {
            throw new MoneyException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array|null
     * @throws \DpDocument\Facades\Money\MoneyException
     */
    private function processResult(ResponseInterface $response): ?array
    {
        if (in_array($response->getStatusCode(), [200, 204])) {
            return $this->parseBody($response->getBody()->getContents());
        } else {
            $error = $this->parseBody($response->getBody()->getContents());

            if (isset($error['error'])) {
                throw new MoneyException($error['error']);
            } elseif (isset($error['critical'])) {
                throw new MoneyException($error['critical']);
            } else {
                throw new MoneyException('Internal server error');
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

    /**
     * @param string ...$currency
     *
     * @throws \DpDocument\Facades\Money\MoneyException
     */
    private function currencyValidator(string ...$currency): void
    {
        foreach ($currency as $c) {
            if (3 !== \strlen($c)) {
                throw new MoneyException('Currency must be 3-symbol currency representation');
            }
        }
    }
}
