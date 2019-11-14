<?php
declare(strict_types=1);

namespace DpDocument\Facades\Notifications\DTO;

/**
 * Class Delivery
 *
 * @package DpDocument\Facades\Notifications\DTO
 * @since   1.2.0
 * DpDocument | Research & Development
 */
final class Delivery
{
    /**
     * @var string
     */
    public $text;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $status;
    /**
     * @var \DateTimeImmutable|null
     */
    public $sentAt;
    /**
     * @var \DateTimeImmutable|null
     */
    public $deliveredAt;
    /**
     * @var string
     */
    public $receiver;
    /**
     * @var string
     */
    public $createdBy;

    /**
     * Delivery constructor.
     *
     * @param string $text
     * @param string $type
     * @param string $status
     * @param \DateTimeImmutable|null $sentAt
     * @param \DateTimeImmutable|null $deliveredAt
     * @param string $receiver
     * @param string $createdBy
     */
    public function __construct(
        string $text,
        string $type,
        ?string $status,
        ?\DateTimeImmutable $sentAt,
        ?\DateTimeImmutable $deliveredAt,
        string $receiver,
        string $createdBy
    ) {
        $this->text = $text;
        $this->type = $type;
        $this->status = $status;
        $this->sentAt = $sentAt;
        $this->deliveredAt = $deliveredAt;
        $this->receiver = $receiver;
        $this->createdBy = $createdBy;
    }

    /**
     * @param array $data
     *
     * @return Delivery
     */
    public static function createFromResponse(array $data): self
    {
        return new self(
            $data['text'],
            $data['type'],
            $data['status'],
            isset($data['sent_at']) ? new \DateTimeImmutable($data['sent_at']) : null,
            isset($data['delivered_at']) ? new \DateTimeImmutable($data['delivered_at']) : null,
            $data['receiver'],
            $data['created_by']
        );
    }
}