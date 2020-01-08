<?php declare(strict_types=1);

namespace App\Service\Upload;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class FileStoreService
{
    /** @var FilesystemInterface */
    private $fileStorage;

    public function __construct(FilesystemInterface $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    /**
     * @param string $targetPath
     * @param $srcFileContent
     * @throws \League\Flysystem\FileExistsException
     */
    public function storeFileFromPath(string $targetPath, $srcFileContent): void
    {
        $storedFilePath = $this->getStoredFilePath($targetPath);

        $stream = fopen($srcFileContent, 'rb+');
        $this->fileStorage->writeStream($storedFilePath, $stream);

        if (is_resource($stream)) {
            fclose($stream);
        }

//        if (!$this->fileStorage->put($storedFilePath, $srcFileContent)) {
//            $msg = 'Error during moving file to destination place: %s';
//            throw new RuntimeException(sprintf($msg, $storedFilePath));
//        }
    }

    /**
     * @param string $targetPath
     * @param $stream
     * @throws \League\Flysystem\FileExistsException
     */
    public function storeFileFromStream(string $targetPath, StreamInterface $stream): void
    {
        $storedFilePath = $this->getStoredFilePath($targetPath);
        $this->fileStorage->writeStream($storedFilePath, $stream->detach());
    }

    /**
     * @param string $filePath
     * @return false|resource
     * @throws FileNotFoundException
     */
    public function readStoredFileAsStream(string $filePath)
    {
        $storedFilePath = $this->getStoredFilePath($filePath);
        return $this->fileStorage->readStream($storedFilePath);
    }

    /**
     * @param string $filePath
     * @return string
     * @throws FileNotFoundException
     */
    public function getMimeType(string $filePath): string
    {
        $storedFilePath = $this->getStoredFilePath($filePath);
        return $this->fileStorage->getMimetype($storedFilePath);
    }

    /**
     * Generate file storage path considering dir hierarchy
     * @param string $fileId
     * @return string
     */
    private function getStoredFilePath(string $fileId): string
    {
        // Rearrange path to store file in subdirectory
        return sprintf('%s/%s',
            substr($fileId, 0, 2),
            substr($fileId, 2)
        );
    }
}

