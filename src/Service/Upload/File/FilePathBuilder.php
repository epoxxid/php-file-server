<?php declare(strict_types=1);

namespace App\Service\Upload\File;

class FilePathBuilder extends AbstractFilePathBuilder
{
    /** @var string */
    private $filePathPrefix;

    public function __construct(string $filePathPrefix)
    {
        $this->filePathPrefix = $filePathPrefix;
    }

    /**
     * Generate relative path for file
     * @param string $identifier
     * @return string
     */
    public function generateFilePath(string $identifier): string
    {
        return $this->addPrefix($this->filePathPrefix, $identifier);
    }
}
