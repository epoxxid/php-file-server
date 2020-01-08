<?php declare(strict_types=1);


namespace App\Service\Upload\Image;

use App\Service\Upload\AbstractUploadResultFactory;

class ImageUploadResultFactory extends AbstractUploadResultFactory
{
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
}
