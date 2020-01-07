<?php declare(strict_types=1);

namespace App\Service\Upload\Image;

use App\Service\Image\ImageManager;
use League\Flysystem\FilesystemInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

class ImageUploader
{
    /** @var FilesystemInterface */
    private $imageStorage;

    /** @var ImageUploadResultFactory */
    private $resultFactory;

    /** @var ImageFilePathBuilder */
    private $filePathBuilder;

    /** @var ImageManager */
    private $imageManager;

    public function __construct(
        FilesystemInterface $imageStorage,
        ImageFilePathBuilder $filePathBuilder,
        ImageManager $imageManager,
        ImageUploadResultFactory $resultFactory
    )
    {
        $this->imageStorage = $imageStorage;
        $this->filePathBuilder = $filePathBuilder;
        $this->resultFactory = $resultFactory;
        $this->imageManager = $imageManager;
    }

    public function upload(UploadedFile $file): ImageUploadResult
    {
        try {
            $fileName = $file->getClientOriginalName();
            $srcFilePath = $file->getRealPath();

            // generate unique file identifier
            $fileExt = $file->getClientOriginalExtension();
            $fileId = $this->filePathBuilder->generateFileIdentifier($fileExt);

            // build path and upload original file
            $originalFilePath = $this->filePathBuilder->generateOriginalImagePath($fileId);
            $this->uploadOriginalImage($originalFilePath, file_get_contents($srcFilePath));

            // create thumbnail file
            $thumbnailFile = $this->imageManager->generateThumbnail($srcFilePath, $fileExt);

            // build path and upload thumbnail file
            $thumbnailPath = $this->filePathBuilder->generateThumbnailPath($fileId);
            $this->uploadThumbnail($thumbnailPath, $thumbnailFile);

            return $this->resultFactory->createResult(
                $fileId,
                $fileName,
                $originalFilePath,
                $thumbnailPath
            );
        } catch (Throwable $e) {
            $msg = 'Unable to move uploaded file to the destination directory: ' . $e->getMessage();
            throw new UploadException($msg, $e->getCode(), $e);
        }
    }

    /**
     * @param string $originalImagePath
     * @param $srcFileContent
     */
    private function uploadOriginalImage(string $originalImagePath, $srcFileContent): void
    {
        $success = $this->imageStorage->put($originalImagePath, $srcFileContent);

        if (!$success) {
            throw new RuntimeException('Error occurred during original image file uploading');
        }
    }

    /**
     * @param string $thumbnailPath
     * @param $srcFileContent
     */
    private function uploadThumbnail(string $thumbnailPath, $srcFileContent): void
    {
        $success = $this->imageStorage->put($thumbnailPath, $srcFileContent);

        if (!$success) {
            throw new RuntimeException('Error occurred during thumbnail file uploading');
        }
    }
}