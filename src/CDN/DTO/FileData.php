<?php
declare(strict_types=1);

namespace DpDocument\Facades\CDN\DTO;

/**
 * Class FileData
 *
 * @package DpDocument\Facades\CDN\DTO
 * @since   1.3.0
 * DpDocument | Research & Development
 */
class FileData
{
    /**
     * @var string
     */
    public $uuid;
    /**
     * @var string
     */
    public $file;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $path;
    /**
     * @var string
     */
    public $href;
    /**
     * @var int
     */
    public $size;
    /**
     * @var \DateTimeImmutable
     */
    public $uploadedAt;
    /**
     * @var string
     */
    public $originalFileName;
    /**
     * @var string
     */
    public $uploadedBy;

    /**
     * FileData constructor.
     *
     * @param string $uuid
     * @param string $file
     * @param string $type
     * @param string $path
     * @param string $href
     * @param int $size
     * @param \DateTimeImmutable $uploadedAt
     * @param string $originalFileName
     * @param string $uploadedBy
     */
    public function __construct(
        string $uuid,
        string $file,
        string $type,
        string $path,
        string $href,
        int $size,
        \DateTimeImmutable $uploadedAt,
        string $originalFileName,
        string $uploadedBy
    ) {
        $this->uuid = $uuid;
        $this->file = $file;
        $this->type = $type;
        $this->path = $path;
        $this->href = $href;
        $this->size = $size;
        $this->uploadedAt = $uploadedAt;
        $this->originalFileName = $originalFileName;
        $this->uploadedBy = $uploadedBy;
    }

    /**
     * @param array $data
     *
     * @return FileData
     */
    public static function createFromResponse(array $data): self
    {
        return new self(
            $data['uuid'],
            $data['file'],
            $data['type'],
            $data['path'],
            $data['href'],
            (int)$data['size'],
            new \DateTimeImmutable($data['uploaded_at']),
            $data['original_file_name'],
            $data['uploaded_by']
        );
    }
}