<?php declare(strict_types=1);

namespace App\Service\Upload\File;

use League\Flysystem\FilesystemInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /** @var FilesystemInterface */
    private $fileStorage;

    /** @var FilePathBuilder */
    private $pathBuilder;

    /** @var FileUploadResultFactory */
    private $resultFactory;

    public function __construct(
        FilesystemInterface $fileStorage,
        FilePathBuilder $pathBuilder,
        FileUploadResultFactory $resultFactory
    )
    {
        $this->fileStorage = $fileStorage;
        $this->pathBuilder = $pathBuilder;
        $this->resultFactory = $resultFactory;
    }

    public function upload(UploadedFile $file): FileUploadResult
    {
        try {
            $fileExt = $file->getClientOriginalExtension();
            $fileId = $this->pathBuilder->generateFileIdentifier($fileExt);

            $srcFilePath = $file->getRealPath();

            $targetFilePath = $this->pathBuilder->generateFilePath($fileId);
            $this->moveFileToDestinationDir($targetFilePath, file_get_contents($srcFilePath));

            $fileName = $file->getClientOriginalName();
            return $this->resultFactory->createResult($fileId, $fileName, $targetFilePath);
        } catch (\Throwable $e) {
            $msg = 'Unable to upload file: ' . $e->getMessage();
            throw new UploadException($msg, $e->getCode(), $e);
        }
    }

    /**
     * @param string $originalImagePath
     * @param $srcFileContent
     */
    private function moveFileToDestinationDir(string $originalImagePath, $srcFileContent): void
    {
        $success = $this->fileStorage->put($originalImagePath, $srcFileContent);

        if (!$success) {
            $template = 'Error during moving uploaded file to destination directory %s';
            throw new RuntimeException(sprintf($template, $originalImagePath));
        }
    }
}
