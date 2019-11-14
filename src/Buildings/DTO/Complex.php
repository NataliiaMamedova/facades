<?php
declare(strict_types=1);

namespace DpDocument\Facades\Buildings\DTO;

/**
 * Class Complex
 *
 * @package DpDocument\Facades\Buildings\DTO
 * @since   1.3.0
 * DpDocument | Research & Development
 */
class Complex extends Building
{
    /**
     * @var MultiApartment[]|[]
     */
    public $multiApartments;

    /**
     * Complex constructor.
     *
     * @param string $id
     * @param MultiApartment[]|[] $multiApartments
     * @param int $type
     * @param string $name
     * @param null|string $googlePlaceId
     * @param Attachment[]|[] $photos
     * @param Housing|null $housing
     * @param Agreement[]|[] $agreements
     * @param Competitor[]|[] $competitors
     * @param string $createdBy
     * @param \DateTimeImmutable $createdAt
     * @param string $editedBy
     * @param \DateTimeImmutable $updatedAt
     * @param null|string $objectType
     */
    public function __construct(
        string $id,
        ?array $multiApartments,
        int $type,
        string $name,
        ?string $googlePlaceId,
        ?array $photos,
        ?Housing $housing,
        ?array $agreements,
        ?array $competitors,
        string $createdBy,
        \DateTimeImmutable $createdAt,
        string $editedBy,
        \DateTimeImmutable $updatedAt,
        ?string $objectType
    ) {
        $this->id = $id;
        $this->multiApartments = $multiApartments;
        $this->type = $type;
        $this->name = $name;
        $this->googlePlaceId = $googlePlaceId;
        $this->photos = $photos;
        $this->housing = $housing;
        $this->agreements = $agreements;
        $this->competitors = $competitors;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
        $this->editedBy = $editedBy;
        $this->updatedAt = $updatedAt;
        $this->objectType = $objectType;
    }

    /**
     * @param array $data
     *
     * @return Complex
     */
    public static function createFromResponse(array $data): self
    {
        return new self(
            $data['id'],
            $data['multi_apartment'],
            (int)$data['type'],
            $data['name'],
            $data['google_place_id'],
            $data['photos'],
            $data['housing'],
            $data['agreements'],
            $data['competitors'],
            $data['created_by'],
            new \DateTimeImmutable($data['created_at']),
            $data['edited_by'],
            new \DateTimeImmutable($data['updated_at']),
            $data['object_type']
        );
    }
}