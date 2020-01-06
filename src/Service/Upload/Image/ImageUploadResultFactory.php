<?php declare(strict_types=1);


namespace App\Service\Upload\Image;

class ImageUploadResultFactory
{
    /** @var string */
    private $host;

    /** @var string */
    private $originalImageUrlPrefix;

    /** @var string */
    private $thumbnailUrlPrefix;

    public function __construct(
        string $host,
        string $originalImageUrlPrefix,
        string $thumbnailUrlPrefix
    )
    {
        $this->host = $host;
        $this->originalImageUrlPrefix = $originalImageUrlPrefix;
        $this->thumbnailUrlPrefix = $thumbnailUrlPrefix;
    }

    public function createResult(string $id, string $originalFileName): ImageUploadResult
    {
        return new ImageUploadResult(
            $id,
            $originalFileName,
            $this->buildUri($this->originalImageUrlPrefix, $id),
            $this->buildUri($this->thumbnailUrlPrefix, $id)
        );
    }

    /**
     * @param string $urlPrefix
     * @param string $id
     * @return string
     */
    private function buildUri(string $urlPrefix, string $id): string
    {
        return sprintf('%s/%s/%s', $this->host, $urlPrefix, $id);
    }
}