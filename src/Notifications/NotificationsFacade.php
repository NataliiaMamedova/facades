<?php
declare(strict_types=1);

namespace DpDocument\Facades\Notifications;

use DpDocument\Facades\Notifications\DTO\Delivery;
use DpDocument\Facades\Notifications\DTO\Notification;
use DpDocument\Facades\Notifications\DTO\NotificationProgress;
use DpDocument\Facades\Notifications\DTO\NotificationResult;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class NotificationsFacade
 *
 * @package DpDocument\Facades\Notifications
 * @since   1.2.0
 * DpDocument | Research & Development
 */
final class NotificationsFacade
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
     * NotificationsFacade constructor.
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
     * Get notifications list
     *
     * @param string $accessToken
     * @param null $search
     * @param int|null $page
     * @param int|null $limit
     * @param array|null $sort
     *
     * @return array
     * @throws NotificationsException
     * @since   1.2.0
     */
    public function getNotifications(
        string $accessToken,
        $search = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $sort = null
    ): array {
        $queryParams = [];
        if (null !== $search) {
            if (is_array($search)) {
                $searchArray = [];
                foreach ($search as $item) {
                    $searchArray[] = $item;
                }
                $queryParams['q'] = $searchArray;
            } else {
                $queryParams['q'] = $search;
            }
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
            $url = $this->baseUrl . '/notifications';
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

            $notifications = [];

            foreach ($data as $item) {
                $notifications[] = Notification::createFromResponse($item);
            }

            return $notifications;
        } catch (\Throwable $throwable) {
            throw new NotificationsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Get progress of notification
     *
     * @param int $id
     * @param string $accessToken
     *
     * @return NotificationProgress
     * @throws NotificationsException
     * @since   1.2.0
     */
    public function getNotificationProgress(int $id, string $accessToken): NotificationProgress
    {
        try {
            $url = $this->baseUrl . '/notifications/' . $id . '/progress';
            $response = $this->client
                ->request('get',
                        $url,
                        [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $accessToken,
                                'Content-Type' => 'application/json'
                            ]
                        ]);
            $data = $this->processResult($response);

            return NotificationProgress::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new NotificationsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Get notification data
     *
     * @param int $id
     * @param string $accessToken
     *
     * @return Notification
     * @throws NotificationsException
     * @since   1.2.0
     */
    public function getNotification(int $id, string $accessToken): Notification
    {
        try {
            $url = $this->baseUrl . '/notifications/' . $id;
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $data = $this->processResult($response);

            return Notification::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new NotificationsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Resend notification
     *
     * @param int $id
     * @param string $accessToken
     *
     * @return bool
     * @throws NotificationsException
     * @since   1.2.0
     */
    public function resendNotification(int $id, string $accessToken): bool
    {
        try {
            $url = $this->baseUrl . '/notifications/' . $id . '/resend';
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

            return true;
        } catch (\Throwable $throwable) {
            throw new NotificationsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Create mail-notification
     *
     * @param string $accessToken
     * @param string $sender
     * @param array $receivers
     * @param string $text
     * @param string $subject
     * @param array|null $placeholders
     * @param \DateTimeImmutable|null $scheduleTime
     *
     * @return NotificationResult
     * @throws NotificationsException
     * @since   1.2.0
     */
    public function sendMails(
        string $sender,
        array $receivers,
        string $text,
        string $subject,
        array $placeholders = null,
        \DateTimeImmutable $scheduleTime = null,
        string $accessToken
    ): NotificationResult {
        try {
            $url = $this->baseUrl . '/notifications/mails';
            $response = $this->client
                ->request('post',
                        $url,
                        [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $accessToken,
                                'Content-Type' => 'application/json'
                            ],
                            'json' => [
                                'from' => $sender,
                                'receivers' => $receivers,
                                'text' => $text,
                                'subject' => $subject,
                                'placeholders' => $placeholders,
                                'schedule_time' => isset($scheduleTime) ? $scheduleTime->format('Y-m-d H:i:s') : null
                            ]
                        ]
                );
            $data = $this->processResult($response);

            return NotificationResult::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new NotificationsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Create sms-notification
     *
     * @param string $accessToken
     * @param array $receivers
     * @param string $text
     * @param string $subject
     * @param array|null $placeholders
     * @param \DateTimeImmutable|null $scheduleTime
     * @param bool $translit
     *
     * @return NotificationResult
     * @throws NotificationsException
     * @since   1.2.0
     */
    public function sendSms(
        array $receivers,
        string $text,
        string $subject,
        array $placeholders = null,
        \DateTimeImmutable $scheduleTime = null,
        bool $translit,
        string $accessToken
    ): NotificationResult {
        try {
            $url = $this->baseUrl . '/notifications/sms';
            $response = $this->client
                ->request('post',
                        $url,
                        [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $accessToken,
                                'Content-Type' => 'application/json'
                            ],
                            'json' => [
                                'receivers' => $receivers,
                                'text' => $text,
                                'subject' => $subject,
                                'placeholders' => $placeholders,
                                'schedule_time' => isset($scheduleTime) ? $scheduleTime->format('Y-m-d H:i:s') : null,
                                'translit' => $translit
                            ]
                        ]);
            $data = $this->processResult($response);

            return NotificationResult::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new NotificationsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * Get delivery data by receiver from notification
     *
     * @param int $notificationId
     * @param string $receiver
     * @param string $accessToken
     *
     * @return Delivery
     * @throws NotificationsException
     * @since   1.2.0
     */
    public function getDeliveryByReceiverAndNotification(
        int $notificationId,
        string $receiver,
        string $accessToken
    ): Delivery {
        try {
            $url = $this->baseUrl . '/notifications/' . $notificationId . '/deliveries/' . $receiver;
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $data = $this->processResult($response);

            return Delivery::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new NotificationsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array|null
     * @throws \DpDocument\Facades\Notifications\NotificationsException
     */
    private function processResult(ResponseInterface $response): ?array
    {
        if (in_array($response->getStatusCode(), [200, 204])) {
            return $this->parseBody($response->getBody()->getContents());
        } else {
            $error = $this->parseBody($response->getBody()->getContents());

            if (isset($error['error'])) {
                throw new NotificationsException($error['error']);
            } elseif (isset($error['critical'])) {
                throw new NotificationsException($error['critical']);
            } else {
                throw new NotificationsException('Internal server error');
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