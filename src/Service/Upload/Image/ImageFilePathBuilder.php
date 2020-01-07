<?php declare(strict_types=1);

namespace App\Service\Upload\Image;

class ImageFilePathBuilder
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

    public function generateFileIdentifier(string $extension): string
    {
        // generate random unique string
        $identifier = md5(uniqid('', true));
        return sprintf(
            '%s/%s.%s',
            substr($identifier, 0, 2),
            substr($identifier, 2),
            $extension
        );
    }

    public function generateOriginalImagePath(string $identifier): string
    {
        return sprintf('%s/%s', $this->originalImagePathPrefix, $identifier);
    }

    public function generateThumbnailPath(string $identifier): string
    {
        return sprintf('%s/%s', $this->thumbnailPathPrefix, $identifier);
    }
}
