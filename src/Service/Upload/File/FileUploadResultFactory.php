<?php declare(strict_types=1);

namespace App\Service\Upload\File;

use App\Service\Upload\AbstractUploadResultFactory;

class FileUploadResultFactory extends AbstractUploadResultFactory
{
    private const URI_SECTION = 'files';

    public function createResult(
        string $fileId,
        string $fileName,
        string $filePath
    ): FileUploadResult
    {
        return new FileUploadResult(
            $fileId,
            $fileName,
            $this->buildUri($filePath, static::URI_SECTION)
        );
    }
}
