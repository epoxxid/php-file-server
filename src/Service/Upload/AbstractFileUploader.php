<?php declare(strict_types=1);

namespace App\Service\Upload;

use League\Flysystem\FilesystemInterface;
use RuntimeException;

abstract class AbstractFileUploader
{
    /** @var FilePathBuilder */
    private $pathBuilder;

    public function __construct(FilePathBuilder $pathBuilder)
    {
        $this->pathBuilder = $pathBuilder;
    }

    protected function moveFileToDestinationDir(
        FilesystemInterface $storage,
        string $targetPath,
        $srcFileContent
    ): void
    {

    }
}
