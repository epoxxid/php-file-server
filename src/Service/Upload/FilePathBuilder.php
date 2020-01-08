<?php declare(strict_types=1);

namespace App\Service\Upload;

class FilePathBuilder
{
    private const ORIGINAL_FILE_NAME = 'file';
    private const THUMBNAIL_FILE_NAME = 'thumb';

    /**
     * Generate unique identifier for file with specified extension
     * @return string
     */
    public function generateFileId(): string
    {
        return md5(uniqid('', true));
    }

    /**
     * Generates path to the original file
     * @param string $fileId
     * @param string $fileExt
     * @return string
     */
    public function generateOriginalFilePath(string $fileId, string $fileExt): string
    {
        return $this->generateSavePath($fileId, self::ORIGINAL_FILE_NAME, $fileExt);
    }

    /**
     * Generates path to the file thumbnail
     * @param string $fileId
     * @param string $fileExt
     * @return string
     */
    public function generateThumbnailFilePath(string $fileId, string $fileExt): string
    {
        return $this->generateSavePath($fileId, self::THUMBNAIL_FILE_NAME, $fileExt);
    }

    /**
     * Add prefix and return identifier
     * @param string $identifier
     * @param string $name
     * @param string $extension
     * @return string
     */
    private function generateSavePath(string $identifier, string $name, string $extension): string
    {
        return sprintf('%s/%s.%s', $identifier, $name, $extension);
    }
}
