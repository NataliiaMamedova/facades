<?php
declare(strict_types=1);

namespace DpDocument\Facades\Notifications\DTO;

/**
 * Class Notification
 *
 * @package DpDocument\Facades\Notifications\DTO
 * @since   1.2.0
 * DpDocument | Research & Development
 */
final class Notification
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var \DateTimeImmutable
     */
    public $createdAt;
    /**
     * @var string
     */
    public $createdBy;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $subject;
    /**
     * @var array
     */
    public $receivers;
    /**
     * @var string
     */
    public $sender;
    /**
     * @var array
     */
    public $placeholders;
    /**
     * @var string
     */
    public $html;
    /**
     * @var string
     */
    public $text;
    /**
     * @var int
     */
    public $sentCounter;
    /**
     * @var int
     */
    public $deliveredCounter;
    /**
     * @var \DateTimeImmutable
     */
    public $scheduleTime;
    /**
     * @var bool
     */
    public $translit;
    /**
     * @var int
     */
    public $rejectedCounter;
    /**
     * @var int
     */
    public $total;

    /**
     * Notification constructor.
     * @param int $id
     * @param \DateTimeImmutable $createdAt
     * @param string $createdBy
     * @param string $type
     * @param string $subject
     * @param array $receivers
     * @param string $sender
     * @param array $placeholders
     * @param string $html
     * @param string $text
     * @param int $sentCounter
     * @param int $deliveredCounter
     * @param \DateTimeImmutable $scheduleTime
     * @param bool $translit
     * @param int $rejectedCounter
     * @param int $total
     */
    public function __construct(
        int $id,
        \DateTimeImmutable $createdAt,
        string $createdBy,
        string $type,
        string $subject,
        array $receivers,
        ?string $sender,
        ?array $placeholders,
        ?string $html,
        string $text,
        int $sentCounter,
        int $deliveredCounter,
        ?\DateTimeImmutable $scheduleTime,
        ?bool $translit,
        int $rejectedCounter,
        int $total
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->createdBy = $createdBy;
        $this->type = $type;
        $this->subject = $subject;
        $this->receivers = $receivers;
        $this->sender = $sender;
        $this->placeholders = $placeholders;
        $this->html = $html;
        $this->text = $text;
        $this->sentCounter = $sentCounter;
        $this->deliveredCounter = $deliveredCounter;
        $this->scheduleTime = $scheduleTime;
        $this->translit = $translit;
        $this->rejectedCounter = $rejectedCounter;
        $this->total = $total;
    }

    /**
     * @param array $data
     * @return Notification
     */
    public static function createFromResponse(array $data): self
    {
        return new self(
            (int)$data['id'],
            new \DateTimeImmutable($data['created_at']),
            $data['created_by'],
            $data['type'],
            $data['subject'],
            $data['receivers'],
            $data['sender'],
            $data['placeholders'],
            $data['html'],
            $data['text'],
            (int)$data['sent_counter'],
            (int)$data['delivered_counter'],
            isset($data['schedule_time']) ? new \DateTimeImmutable($data['schedule_time']) : null,
            $data['translit'],
            (int)$data['rejected_counter'],
            (int)$data['total']
        );
    }
}