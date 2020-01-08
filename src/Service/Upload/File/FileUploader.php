<?php declare(strict_types=1);

namespace App\Service\Upload\File;

use App\Service\Upload\AbstractFileUploader;
use App\Service\Upload\FilePathBuilder;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

class FileUploader extends AbstractFileUploader
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
            $fileId = $this->pathBuilder->generateFileId();

            $srcFilePath = $file->getRealPath();

            $targetFilePath = $this->pathBuilder->generateOriginalFilePath($fileId, $fileExt);
            $this->moveFileToDestinationDir(
                $this->fileStorage,
                $targetFilePath,
                file_get_contents($srcFilePath)
            );

            return $this->resultFactory->createResult(
                $fileId,
                $file->getClientOriginalName(),
                $targetFilePath
            );
        } catch (Throwable $e) {
            $msg = 'Unable to upload file: ' . $e->getMessage();
            throw new UploadException($msg, $e->getCode(), $e);
        }
    }
}
