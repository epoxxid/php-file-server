<?php declare(strict_types=1);


namespace App\Service\Upload\Image;

use App\Service\Upload\AbstractFileUploadResult;

class ImageUploadResult extends AbstractFileUploadResult
{
    /** @var string */
    private $thumbnailUri;

    public function __construct(
        string $fileId,
        string $fileName,
        string $originalImageUri,
        string $thumbnailUri
    )
    {
        parent::__construct($fileId, $fileName, $originalImageUri);
        $this->thumbnailUri = $thumbnailUri;
    }

    public function getOriginalImageUri(): string
    {
        return $this->fileUri;
    }

    public function getThumbnailUri(): ?string
    {
        return $this->thumbnailUri;
    }

    public function setThumbnailUri(string $thumbnailUri): void
    {
        $this->thumbnailUri = $thumbnailUri;
    }
}