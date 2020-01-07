<?php declare(strict_types=1);

namespace App\Service\Image;

use Intervention\Image\Image;
use RuntimeException;
use Throwable;

class ImageManager
{
    /** @var \Intervention\Image\ImageManager */
    private $engine;

    /** @var int */
    private $maxThumbnailSize;

    /** @var bool */
    private $keepThumbnailRatio;

    /** @var int */
    private $thumbnailQuality;

    public function __construct(
        int $maxThumbnailSize,
        bool $keepThumbnailRatio,
        int $thumbnailQuality,
        string $driver = 'gd'
    )
    {
        $this->engine = new \Intervention\Image\ImageManager(['driver' => $driver]);
        $this->maxThumbnailSize = $maxThumbnailSize;
        $this->keepThumbnailRatio = $keepThumbnailRatio;
        $this->thumbnailQuality = $thumbnailQuality;
    }

    /**
     * @param string $filePath
     * @param string $fileExt
     * @return string
     */
    public function generateThumbnail(string $filePath, string $fileExt): string
    {
        try {
            $image = $this->engine->make($filePath);
            [$targetWidth, $targetHeight] = $this->calculateThumbnailSize($image->getWidth(), $image->getHeight());

            $image->resize($targetWidth, $targetHeight);

            if (!$this->keepThumbnailRatio) {
                $minImageSize = min($targetWidth, $targetHeight);
                $image->crop($minImageSize, $minImageSize);
            }

            return (string)$image->encode($fileExt, $this->thumbnailQuality);
        } catch (Throwable $e) {
            throw new RuntimeException(
                sprintf(
                    'Unable to create thumbnail from file %s: %s',
                    $filePath,
                    $e->getMessage()
                )
            );
        }
    }

    private function calculateThumbnailSize(int $width, int $height): array
    {
        $targetWidth = $width;
        $targetHeight = $height;

        if ($targetWidth > $this->maxThumbnailSize) {
            $targetWidth = $this->maxThumbnailSize;
            $ratio = $width / $targetWidth;
            $targetHeight = $height / $ratio;
        } elseif ($targetHeight > $this->maxThumbnailSize) {
            $targetHeight = $this->maxThumbnailSize;
            $ratio = $height / $targetHeight;
            $targetWidth = $height / $ratio;
        }

        return [$targetWidth, $targetHeight];
    }
}
