<?php
declare(strict_types=1);

namespace DpDocument\Facades\Money\DTO;

/**
 * Class Subscriber
 *
 * @package DpDocument\Facades\Money\DTO
 * @since   1.1.0
 * DpDocument | Research & Development
 */
final class Subscriber
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $email;
    /**
     * @var null|string
     */
    public $mobileNumber;
    /**
     * @var string
     */
    public $createdBy;
    /**
     * @var string
     */
    public $updatedBy;
    /**
     * @var \DateTimeImmutable
     */
    public $createdAt;
    /**
     * @var \DateTimeImmutable
     */
    public $updatedAt;

    public function __construct(
        int $id,
        string $email,
        ?string $mobileNumber,
        string $createdBy,
        string $updatedBy,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id           = $id;
        $this->email        = $email;
        $this->mobileNumber = $mobileNumber;
        $this->createdBy    = $createdBy;
        $this->updatedBy    = $updatedBy;
        $this->createdAt    = $createdAt;
        $this->updatedAt    = $updatedAt;
    }

    /**
     * @param array $data
     *
     * @return \DpDocument\Facades\Money\DTO\Subscriber
     */
    public static function createFromResponse(array $data): self
    {
        return new self(
            (int)$data['id'],
            $data['email'],
            $data['mobile_number'],
            $data['created_by'],
            $data['updated_by'],
            new \DateTimeImmutable($data['created_at']),
            new \DateTimeImmutable($data['updated_at'])
        );
    }
}
