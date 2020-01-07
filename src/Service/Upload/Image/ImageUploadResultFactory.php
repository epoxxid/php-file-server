<?php declare(strict_types=1);


namespace App\Service\Upload\Image;

class ImageUploadResultFactory
{
    /** @var string */
    private $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function createResult(
        string $fileId,
        string $fileName,
        string $originalFilePath,
        string $thumbnailPath
    ): ImageUploadResult
    {
        return new ImageUploadResult(
            $fileId,
            $fileName,
            $this->buildUri($originalFilePath),
            $this->buildUri($thumbnailPath)
        );
    }

/**
 * @param string $filePath
 * @return string
 */
    private function buildUri(string $filePath): string
    {
        return sprintf('%s/images/%s', $this->host, $filePath);
    }
}