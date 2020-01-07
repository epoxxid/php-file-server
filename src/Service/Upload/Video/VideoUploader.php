<?php declare(strict_types=1);

namespace App\Service\Upload\Video;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

class VideoUploader
{

    public function __construct(
        FilesystemInterface $videoStorage,
        VideoFilePathBuilder $pathBuilder,
        VideoManager $videoManager,
        VideoUploadResultFactory $resultFactory
    )
    {
    }

    public function upload(UploadedFile $video): VideoUploadResult
    {
        try {


            return $this->resultFactory->createResult(
                $fileId,
                $file->getClientOriginalName(),
                $videoFilePath,
                $thumbnailPath
            );
        } catch (Throwable $e) {
            $msg = 'Unable to move uploaded file to the destination directory: ' . $e->getMessage();
            throw new UploadException($msg, $e->getCode(), $e);
        }
    }
}
