<?php
declare(strict_types=1);

namespace DpDocumentRnD\Facades\Buildings\DTO;

/**
 * Class Buildings
 *
 * @package DpDocument\Facades\Buildings
 * @since   1.3.0
 * DpDocument | Research & Development
 */
abstract class Building
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var int|null
     */
    public $type;
    /**
     * @var null|string
     */
    public $googlePlaceId;
    /**
     * @var Attachment[]|[]
     */
    public $photos;
    /**
     * @var Housing|null
     */
    public $housing;
    /**
     * @var Agreement[]|[]
     */
    public $agreements;
    /**
     * @var Competitor[]|[]
     */
    public $competitors;
    /**
     * @var string
     */
    public $createdBy;
    /**
     * @var string
     */
    public $editedBy;
    /**
     * @var \DateTimeImmutable
     */
    public $createdAt;
    /**
     * @var \DateTimeImmutable
     */
    public $updatedAt;
    /**
     * @var null|string
     */
    public $objectType;
}