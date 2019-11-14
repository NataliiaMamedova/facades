<?php
declare(strict_types=1);

namespace DpDocument\Adapters\Tests\Money;

use DpDocument\Facades\Money\DTO\ActiveRate;
use DpDocument\Facades\Money\DTO\Rate;
use DpDocument\Facades\Money\DTO\Subscriber;
use DpDocument\Facades\Money\MoneyException;
use DpDocument\Facades\Money\MoneyFacade;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class MoneyFacadeTest
 *
 * @package DpDocument\Adapters\Tests\Money
 * DpDocument | Research & Development
 */
class MoneyFacadeTest extends TestCase
{
    private static $baseApiUrl = 'http://some.host/api';

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessCurrenciesList()
    {
        $responseBody = ["UAH", "USD", "EUR"];

        $client = $this->createClient($responseBody, 200);

        $moneyFacade = new MoneyFacade($client, self::$baseApiUrl);
        $this->assertEquals($responseBody, $moneyFacade->getCurrenciesList());
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testFailedCurrenciesList()
    {
        $responseBody = null;

        $client = $this->createClient($responseBody, 500);
        $this->expectException(MoneyException::class);

        $moneyFacade = new MoneyFacade($client, self::$baseApiUrl);
        $moneyFacade->getCurrenciesList();
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessResponseFromCurrencyConverter()
    {
        $dataset = [
            ['amount' => 1, 'currency' => 'USD', 'response' => ['amount' => 27.10, 'currency' => 'UAH']],
            ['amount' => 100.1, 'currency' => 'USD', 'response' => ['amount' => 28.10, 'currency' => 'UAH']],
            ['amount' => 950.99, 'currency' => 'UAH', 'response' => ['amount' => 28.10, 'currency' => 'USD']],
        ];

        foreach ($dataset as $data) {
            $client = $this->createClient($data['response'], 200);

            $moneyFacade       = new MoneyFacade($client, self::$baseApiUrl);
            $converterResponse = $moneyFacade
                ->convertCurrency($data['amount'], $data['currency'], $data['response']['currency']);

            $this->assertInstanceOf(\DpDocument\Facades\Money\DTO\ConverterResult::class, $converterResponse);
            $this->assertEquals($data['response']['amount'], $converterResponse->amount);
            $this->assertEquals($data['response']['currency'], $converterResponse->currency);
        }
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testFailedResponseFromCurrencyConverter()
    {
        $dataset = [
            ['amount' => 100.1, 'currency' => 'USDDD', 'response' => ['amount' => 27.10, 'currency' => 'UAH']],
            ['amount' => '100.1.122', 'currency' => 'USD', 'response' => ['amount' => 28.10, 'currency' => 'UAH']],
            [
                'amount'   => '9999999999999999999999999999999999.999999',
                'currency' => 'UAH',
                'response' => ['amount' => 28.10, 'currency' => 'USD']
            ],
        ];

        foreach ($dataset as $data) {
            $client = $this->createClient($data['response'], 500);

            $this->expectException(MoneyException::class);

            $moneyFacade       = new MoneyFacade($client, self::$baseApiUrl);
            $converterResponse = $moneyFacade
                ->convertCurrency($data['amount'], $data['currency'], $data['response']['currency']);

        }
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSuccessNewActiveRate()
    {
        $token = 'test-token';
        $data  = [
            'id'               => 1,
            'base_currency'    => 'USD',
            'counter_currency' => 'UAH',
            'created_by'       => 'admin',
            'created_at'       => '2018-02-06T12:46:29+00:00'
        ];

        $client = $this->createClient($data, 200);

        $moneyFacade   = new MoneyFacade($client, self::$baseApiUrl);
        $newActiveRate = $moneyFacade->newActiveRate($data['base_currency'], $data['counter_currency'], $token);

        $this->assertInstanceOf(ActiveRate::class, $newActiveRate);
        $this->assertEquals($data['id'], $newActiveRate->id);
        $this->assertEquals($data['base_currency'], $newActiveRate->baseCurrency);
        $this->assertEquals($data['counter_currency'], $newActiveRate->counterCurrency);
        $this->assertEquals($data['created_by'], $newActiveRate->createdBy);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $newActiveRate->createdAt);
    }

    public function testFailedNewActiveRate()
    {
        $token = 'test-token';
        $data  = [
            'id'               => 1,
            'base_currency'    => 'USDD',
            'counter_currency' => 'UAH',
            'created_by'       => 'admin',
            'created_at'       => '2018-02-06T12:46:29+00:00'
        ];

        $client = $this->createClient($data, 400);

        $this->expectException(MoneyException::class);

        $moneyFacade   = new MoneyFacade($client, self::$baseApiUrl);
        $newActiveRate = $moneyFacade->newActiveRate($data['base_currency'], $data['counter_currency'], $token);
    }

    public function testSuccessDeleteActiveRate()
    {
        $client = $this->createClient(null, 204);

        $moneyFacade  = new MoneyFacade($client, self::$baseApiUrl);
        $deleteStatus = $moneyFacade->deleteActiveRate(1, 'test_token');

        $this->assertTrue($deleteStatus);
    }

    public function testFailedDeleteActiveRate()
    {
        $client = $this->createClient(['error' => 'Not found'], 404);

        $moneyFacade = new MoneyFacade($client, self::$baseApiUrl);
        $this->expectException(MoneyException::class);

        $deleteStatus = $moneyFacade->deleteActiveRate(1, 'test_token');
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAllActiveRates()
    {
        $data = [
            [
                'id'               => 1,
                'base_currency'    => 'USD',
                'counter_currency' => 'UAH',
                'created_by'       => 'admin',
                'created_at'       => '2018-02-06T12:46:29+00:00'
            ],
            [
                'id'               => 2,
                'base_currency'    => 'EUR',
                'counter_currency' => 'UAH',
                'created_by'       => 'admin',
                'created_at'       => '2018-02-06T12:46:29+00:00'
            ]
        ];

        $client = $this->createClient($data, 200);

        $moneyFacade    = new MoneyFacade($client, self::$baseApiUrl);
        $allActiveRates = $moneyFacade->getAllActiveRates();

        $this->assertContainsOnlyInstancesOf(ActiveRate::class, $allActiveRates);
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetRatesForPeriods()
    {
        $data = [
            [
                'id'               => 1,
                'base_currency'    => 'USD',
                'counter_currency' => 'UAH',
                'rate'             => 27.8595,
                'date'             => '2018-02-02T00:00:00+00:00'
            ],
            [
                'id'               => 2,
                'base_currency'    => 'USD',
                'counter_currency' => 'UAH',
                'rate'             => 8.1511,
                'date'             => '2018-02-03T00:00:00+00:00'
            ],
        ];

        $periods = ['today', 'week', 'month', 'year'];

        foreach ($periods as $period) {
            $client = $this->createClient($data, 200);

            $moneyFacade    = new MoneyFacade($client, self::$baseApiUrl);
            $allActiveRates = $moneyFacade->getRatesFor($period);

            $this->assertContainsOnlyInstancesOf(Rate::class, $allActiveRates);
        }
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testNewSubscriber()
    {
        $token  = 'test-token';
        $email  = 'bs@dp.document.net';
        $mobile = '3801012345678';
        $data   = [
            'id'            => 1,
            'email'         => $email,
            'mobile_number' => null,
            'created_by'    => 'admin',
            'updated_by'    => 'admin',
            'created_at'    => '2018-02-06T12:46:29+00:00',
            'updated_at'    => '2018-02-06T12:46:29+00:00',
        ];

        // Without mobile
        $client        = $this->createClient($data, 200);
        $moneyFacade   = new MoneyFacade($client, self::$baseApiUrl);
        $newSubscriber = $moneyFacade->newSubscriber($data['email'], $data['mobile_number'], $token);
        $this->assertSubscriber($data, $newSubscriber);

        /// With mobile
        $data['mobile_number'] = $mobile;
        $client                = $this->createClient($data, 200);
        $moneyFacade           = new MoneyFacade($client, self::$baseApiUrl);
        $newSubscriber         = $moneyFacade->newSubscriber($data['email'], $data['mobile_number'], $token);
        $this->assertSubscriber($data, $newSubscriber);
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateSubscriber()
    {
        $token = 'test-token';
        $data  = [
            'id'            => 1,
            'email'         => 'bs@dp.document.net',
            'mobile_number' => '3801012345678',
        ];

        $client       = $this->createClient(null, 200);
        $moneyFacade  = new MoneyFacade($client, self::$baseApiUrl);
        $updateResult = $moneyFacade->updateSubscriber($data['id'], $data['email'], $data['mobile_number'], $token);
        $this->assertTrue($updateResult);
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteSubscriber()
    {
        $token = 'test-token';

        $client       = $this->createClient(null, 200);
        $moneyFacade  = new MoneyFacade($client, self::$baseApiUrl);
        $updateResult = $moneyFacade->deleteSubscriber(1, $token);
        $this->assertTrue($updateResult);
    }

    /**
     * @param array $data
     * @param       $subscriber
     *
     * @throws \Exception
     */
    private function assertSubscriber(array $data, $subscriber)
    {
        $this->assertInstanceOf(Subscriber::class, $subscriber);
        $this->assertEquals($data['id'], $subscriber->id);
        $this->assertEquals($data['email'], $subscriber->email);
        $this->assertEquals($data['mobile_number'], $subscriber->mobileNumber);
        $this->assertEquals($data['created_by'], $subscriber->createdBy);
        $this->assertEquals($data['updated_by'], $subscriber->updatedBy);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $subscriber->createdAt);
        $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $subscriber->updatedAt);
    }

    /**
     * @throws \DpDocument\Facades\Money\MoneyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetSubscribers()
    {
        $data = [
            [
                'id'            => 1,
                'email'         => 'bs@dp.document.net',
                'mobile_number' => '3801012345678',
                'created_by'    => 'admin',
                'updated_by'    => 'admin',
                'created_at'    => '2018-02-06T12:46:29+00:00',
                'updated_at'    => '2018-02-06T12:46:29+00:00',
            ],
            [
                'id'            => 1,
                'email'         => 'bs@dp.document.net',
                'mobile_number' => '3801087650321',
                'created_by'    => 'admin',
                'updated_by'    => 'admin',
                'created_at'    => '2018-02-06T12:46:29+00:00',
                'updated_at'    => '2018-02-06T12:46:29+00:00',
            ],
        ];

        $client      = $this->createClient($data, 200);
        $moneyFacade = new MoneyFacade($client, self::$baseApiUrl);
        $subscribers = $moneyFacade->getSubscribers('test_token_here');
        $this->assertContainsOnlyInstancesOf(Subscriber::class, $subscribers);
    }

    /**
     * @throws \ReflectionException
     */
    public function testParseBody()
    {
        $client = $this->createClient(null, 400);
        $facade = new MoneyFacade($client, self::$baseApiUrl);

        $reflection = new \ReflectionClass(MoneyFacade::class);
        $pMethod    = $reflection->getMethod('parseBody');
        $pMethod->setAccessible(true);

        $this->assertEquals(['test' => true], $pMethod->invoke($facade, '{"test": true}'));
        $this->assertEquals(null, $pMethod->invoke($facade, '{{"error"'));
    }

    /**
     * @throws \ReflectionException
     */
    public function testProcessResult()
    {
        $client = $this->createClient(['test' => true], 400);
        $facade = new MoneyFacade($client, self::$baseApiUrl);

        $reflection = new \ReflectionClass(MoneyFacade::class);
        $pMethod    = $reflection->getMethod('processResult');
        $pMethod->setAccessible(true);

        $this->expectException(MoneyException::class);
        $pMethod->invoke($facade, new Response(400, [], \json_encode(['error' => 'Error'])));
        $pMethod->invoke($facade, new Response(400, [], \json_encode(['critical' => 'Critical error'])));
        $pMethod->invoke($facade, new Response(400, [], 'Internal server error'));
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
