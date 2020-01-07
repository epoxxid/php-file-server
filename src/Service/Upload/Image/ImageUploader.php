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
    private $pathBuilder;

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
        $this->pathBuilder = $filePathBuilder;
        $this->resultFactory = $resultFactory;
        $this->imageManager = $imageManager;
    }

    public function upload(UploadedFile $file): ImageUploadResult
    {
        try {
            // generate unique file identifier
            $fileExt = $file->getClientOriginalExtension();
            $fileId = $this->pathBuilder->generateFileIdentifier($fileExt);

            $srcFilePath = $file->getRealPath();

            // build path and upload original file
            $originalFilePath = $this->pathBuilder->generateOriginalImagePath($fileId);
            $this->moveOriginalImageToDestinationDir($originalFilePath, file_get_contents($srcFilePath));

            // create thumbnail file
            $thumbnailFile = $this->imageManager->generateThumbnail($srcFilePath, $fileExt);

            // build path and upload thumbnail file
            $thumbnailPath = $this->pathBuilder->generateThumbnailPath($fileId);
            $this->moveThumbnailToDestinationDir($thumbnailPath, $thumbnailFile);

            return $this->resultFactory->createResult(
                $fileId,
                $file->getClientOriginalName(),
                $originalFilePath,
                $thumbnailPath
            );
        } catch (Throwable $e) {
            $msg = 'Unable to upload image: ' . $e->getMessage();
            throw new UploadException($msg, $e->getCode(), $e);
        }
    }

    /**
     * @param string $originalImagePath
     * @param $srcFileContent
     */
    private function moveOriginalImageToDestinationDir(string $originalImagePath, $srcFileContent): void
    {
        $success = $this->imageStorage->put($originalImagePath, $srcFileContent);

        if (!$success) {
            $template = 'Error during moving uploaded image to destination directory %s';
            throw new RuntimeException(sprintf($template, $originalImagePath));
        }
    }

    /**
     * @param string $thumbnailPath
     * @param $srcFileContent
     */
    private function moveThumbnailToDestinationDir(string $thumbnailPath, $srcFileContent): void
    {
        $success = $this->imageStorage->put($thumbnailPath, $srcFileContent);

        if (!$success) {
            $template = 'Error during moving image thumbnail to destination directory %s';
            throw new RuntimeException(sprintf($template, $thumbnailPath));
        }
    }
}