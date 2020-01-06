<?php declare(strict_types=1);

namespace App\Service\Upload\Image;

use League\Flysystem\FilesystemInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

class ImageUploader
{
    /** @var FilesystemInterface */
    private $imagesOriginalStorage;

    /** @var FilesystemInterface */
    private $imagesMediumStorage;

    /** @var ImageUploadResultFactory */
    private $resultFactory;

    public function __construct(
        FilesystemInterface $imagesOriginalStorage,
        FilesystemInterface $imagesThumbnailStorage,
        ImageUploadResultFactory $resultFactory
    )
    {
        $this->imagesOriginalStorage = $imagesOriginalStorage;
        $this->imagesMediumStorage = $imagesThumbnailStorage;
        $this->resultFactory = $resultFactory;
    }

    public function upload(UploadedFile $file): ImageUploadResult
    {
        try {
            $fileName = $file->getFilename();
            $srcFilePath = $file->getRealPath();

            $identifier = md5(uniqid('', true));
            $targetFilePath = sprintf(
                '%s/%s.%s',
                substr($identifier, 0, 2),
                substr($identifier, 2),
                $file->getClientOriginalExtension()
            );

            $this->uploadOriginalImage($targetFilePath, file_get_contents($srcFilePath));
//            $this->uploadMediumImage($targetFilePath, $srcFileContent);
//            $this->uploadSmallImage($targetFilePath, $srcFileContent);

            return $this->resultFactory->createResult($targetFilePath, $fileName);
        } catch (Throwable $e) {
            $msg = 'Unable to move uploaded file to the destination directory: ' . $e->getMessage();
            throw new UploadException($msg, $e->getCode(), $e);
        }
    }

    /**
     * @param string $targetFilePath
     * @param $srcFileContent
     */
    private function uploadOriginalImage(string $targetFilePath, $srcFileContent): void
    {
        $success = $this->imagesOriginalStorage->put($targetFilePath, $srcFileContent);

        if (!$success) {
            throw new RuntimeException('Error occurred during original image file uploading');
        }
    }

}