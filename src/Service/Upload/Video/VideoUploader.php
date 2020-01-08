<?php declare(strict_types=1);

namespace App\Service\Upload\Video;

use App\Service\Upload\FilePathBuilder;
use App\Service\Upload\FileStoreService;
use App\Service\Video\VideoThumbnailGenerator;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

class VideoUploader
{
    /** @var FilePathBuilder */
    private $pathBuilder;

    /** @var VideoThumbnailGenerator */
    private $thumbnailGenerator;

    /** @var VideoUploadResultFactory */
    private $resultFactory;

    /** @var FileStoreService */
    private $storeService;

    public function __construct(
        FileStoreService $storeService,
        FilePathBuilder $pathBuilder,
        VideoThumbnailGenerator $thumbnailGenerator,
        VideoUploadResultFactory $resultFactory
    )
    {
        $this->pathBuilder = $pathBuilder;
        $this->thumbnailGenerator = $thumbnailGenerator;
        $this->resultFactory = $resultFactory;
        $this->storeService = $storeService;
    }

    public function upload(UploadedFile $file): VideoUploadResult
    {
        try {
            $fileExt = $file->getClientOriginalExtension();
            $fileId = $this->pathBuilder->generateFileId();
            
            $srcFilePath = $file->getRealPath();

            $videoFilePath = $this->pathBuilder->generateOriginalFilePath($fileId, $fileExt);
            $this->storeService->storeFileFromPath($videoFilePath, $srcFilePath);

//            $thumbnail = $this->thumbnailGenerator->generateThumbnail($srcFilePath);
//            $thumbImagePath = $this->pathBuilder->generateThumbnailFilePath($fileId, 'jpg');
//            $this->storeService->storeFileFromPath($thumbImagePath, $thumbnail);

            return $this->resultFactory->createResult(
                $fileId,
                $file->getClientOriginalName(),
                $videoFilePath,
                $thumbImagePath = ''
            );
        } catch (Throwable $e) {
            $msg = 'Unable to move uploaded video to the destination directory: ' . $e->getMessage();
            throw new UploadException($msg, $e->getCode(), $e);
        }
    }
}
