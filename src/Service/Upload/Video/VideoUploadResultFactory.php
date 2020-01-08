<?php declare(strict_types=1);

namespace App\Service\Upload\Video;

use App\Service\Upload\AbstractUploadResultFactory;

class VideoUploadResultFactory extends AbstractUploadResultFactory
{
    public function createResult(
        string $fileId,
        string $fileName,
        string $videoFilePath,
        string $thumbnailPath
    ): VideoUploadResult
    {
        return new VideoUploadResult(
            $fileId,
            $fileName,
            $this->buildUri($videoFilePath),
            $this->buildUri($thumbnailPath)
        );
    }
}
