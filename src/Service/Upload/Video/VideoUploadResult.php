<?php declare(strict_types=1);

namespace App\Service\Upload\Video;

use App\Service\Upload\AbstractFileUploadResult;

class VideoUploadResult extends AbstractFileUploadResult
{
    private const UPLOADED_ENTITY_TYPE = 'video';

    /** @var string */
    private $thumbnailImageUri;

    public function __construct(
        string $fileId,
        string $fileName,
        string $videoFileUri,
        string $thumbnailImageUri
    )
    {
        parent::__construct($fileId, $fileName, $videoFileUri);
        $this->thumbnailImageUri = $thumbnailImageUri;
    }

    public function getVideoFileUri(): string
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
