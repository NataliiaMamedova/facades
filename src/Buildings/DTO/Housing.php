<?php
declare(strict_types=1);

namespace DpDocument\Facades\Buildings\DTO;

/**
 * Class Housing
 *
 * @package DpDocument\Facades\Buildings
 * @since   1.3.0
 * DpDocument | Research & Development
 */
class Housing
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $address;
    /**
     * @var Building[]|[]
     */
    public $buildings;
    /**
     * @var string
     */
    public $createdBy;
    /**
     * @var \DateTimeImmutable
     */
    public $createdAt;
    /**
     * @var string
     */
    public $editedBy;
    /**
     * @var \DateTimeImmutable
     */
    public $updatedAt;

    /**
     * Housing constructor.
     *
     * @param int $id
     * @param string $name
     * @param string $address
     * @param Building[]|[] $buildings
     * @param string $createdBy
     * @param \DateTimeImmutable $createdAt
     * @param string $editedBy
     * @param \DateTimeImmutable $updatedAt
     */
    public function __construct(
        int $id,
        string $name,
        string $address,
        ?array $buildings,
        string $createdBy,
        \DateTimeImmutable $createdAt,
        string $editedBy,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->buildings = $buildings;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
        $this->editedBy = $editedBy;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param array $data
     *
     * @return Housing
     */
    public static function createFromResponse(array $data): self
    {
        return new self(
            (int)$data['id'],
            $data['name'],
            $data['address'],
            $data['buildings'] ?? null,
            $data['created_by'],
            new \DateTimeImmutable($data['created_at']),
            $data['edited_by'],
            new \DateTimeImmutable($data['updated_at'])
        );
    }
}