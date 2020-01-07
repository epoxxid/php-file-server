<?php declare(strict_types=1);

namespace App\Service\Upload\Image;

use App\Service\Upload\File\AbstractFilePathBuilder;

class ImageFilePathBuilder extends AbstractFilePathBuilder
{
    /** @var string */
    private $originalImagePathPrefix;

    /** @var string */
    private $thumbnailPathPrefix;

    public function __construct(
        string $originalImagePathPrefix,
        string $thumbnailPathPrefix
    )
    {
        $this->originalImagePathPrefix = $originalImagePathPrefix;
        $this->thumbnailPathPrefix = $thumbnailPathPrefix;
    }

    /**
     * Generate relative path for image
     * @param string $identifier
     * @return string
     */
    public function generateOriginalImagePath(string $identifier): string
    {
        return $this->addPrefix($this->originalImagePathPrefix, $identifier);
    }

    /**
     * Generate relative path for image thumbnail
     * @param string $identifier
     * @return string
     */
    public function generateThumbnailPath(string $identifier): string
    {
        return $this->addPrefix($this->thumbnailPathPrefix, $identifier);
    }
}
