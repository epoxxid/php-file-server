<?php declare(strict_types=1);

namespace App\Service\Upload\Image;

use App\Service\Image\ImageThumbnailGenerator;
use App\Service\Upload\FilePathBuilder;
use App\Service\Upload\FileStoreService;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

class ImageUploader
{
    /** @var ImageUploadResultFactory */
    private $resultFactory;

    /** @var FilePathBuilder */
    private $pathBuilder;

    /** @var ImageThumbnailGenerator */
    private $thumbnailGenerator;
    /**
     * @var FileStoreService
     */
    private $storeService;

    public function __construct(
        FilePathBuilder $filePathBuilder,
        FileStoreService $storeService,
        ImageThumbnailGenerator $thumbnailGenerator,
        ImageUploadResultFactory $resultFactory
    )
    {
        $this->pathBuilder = $filePathBuilder;
        $this->resultFactory = $resultFactory;
        $this->thumbnailGenerator = $thumbnailGenerator;
        $this->storeService = $storeService;
    }

    public function upload(UploadedFile $file): ImageUploadResult
    {
        try {
            // generate unique file identifier
            $fileExt = $file->getClientOriginalExtension();
            $fileId = $this->pathBuilder->generateFileId();

            $srcFilePath = $file->getRealPath();

            // build path and upload original file
            $origImagePath = $this->pathBuilder->generateOriginalFilePath($fileId, $fileExt);
            $this->storeService->storeFileFromPath($origImagePath, $srcFilePath);

            // create thumbnail file
            $thumbnailFile = $this->thumbnailGenerator->generateThumbnail($srcFilePath, $fileExt);

            // build path and upload thumbnail file
            $thumbnailPath = $this->pathBuilder->generateThumbnailFilePath($fileId, $fileExt);
            $this->storeService->storeFileFromStream($thumbnailPath, $thumbnailFile);

            return $this->resultFactory->createResult(
                $fileId,
                $file->getClientOriginalName(),
                $origImagePath,
                $thumbnailPath
            );
        } catch (Throwable $e) {
            $msg = 'Unable to upload image: ' . $e->getMessage();
            throw new UploadException($msg, $e->getCode(), $e);
        }
    }
}
