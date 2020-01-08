<?php declare(strict_types=1);


namespace App\Service\Upload\Image;

use App\Service\Upload\AbstractFileUploadResult;

class ImageUploadResult extends AbstractFileUploadResult
{
    private const UPLOADED_ENTITY_TYPE = 'image';

    /** @var string */
    private $thumbnailImageUri;

    public function __construct(
        string $fileId,
        string $fileName,
        string $originalImageUri,
        string $thumbnailImageUri
    )
    {
        parent::__construct($fileId, $fileName, $originalImageUri);
        $this->thumbnailImageUri = $thumbnailImageUri;
    }

    public function getOriginalImageUri(): string
    {
        return $this->fileUri;
    }

    public function getThumbnailImageUri(): ?string
    {
        return $this->thumbnailImageUri;
    }

    /** @inheritDoc */
    public function getType(): string
    {
        return self::UPLOADED_ENTITY_TYPE;
    }
}