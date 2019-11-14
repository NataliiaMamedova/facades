<?php
declare(strict_types=1);

namespace DpDocument\Facades\Money\DTO;

/**
 * Class ActiveRate
 *
 * @package DpDocument\Facades\Money\DTO
 * @since   1.0.0
 * DpDocument | Research & Development
 */
final class ActiveRate
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $baseCurrency;
    /**
     * @var string
     */
    public $counterCurrency;
    /**
     * @var string
     */
    public $createdBy;
    /**
     * @var \DateTimeImmutable
     */
    public $createdAt;

    /**
     * ActiveRate constructor.
     *
     * @param int                $id
     * @param string             $baseCurrency
     * @param string             $counterCurrency
     * @param string             $createdBy
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(
        int $id,
        string $baseCurrency,
        string $counterCurrency,
        string $createdBy,
        \DateTimeImmutable $createdAt
    ) {
        $this->id              = $id;
        $this->baseCurrency    = $baseCurrency;
        $this->counterCurrency = $counterCurrency;
        $this->createdBy       = $createdBy;
        $this->createdAt       = $createdAt;
    }

    /**
     * @param array $data
     *
     * @return \DpDocument\Facades\Money\DTO\ActiveRate
     */
    public static function createFromResponse(array $data): self
    {
        return new self(
            (int)$data['id'],
            $data['base_currency'],
            $data['counter_currency'],
            $data['created_by'],
            new \DateTimeImmutable($data['created_at'])
        );
    }
}
