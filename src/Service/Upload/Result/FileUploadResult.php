<?php declare(strict_types=1);


namespace App\Service\Upload;

class FileUploadResult
{
    /** @var string */
    private $relativeFilePath;

    /** @var string */
    private $pathPrefix;

    /** @var string */
    private $host;

    public function __construct(string $relativeFilePath, string $pathPrefix, string $host)
    {
        $this->relativeFilePath = $relativeFilePath;
        $this->pathPrefix = $pathPrefix;
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        $filePath = $this->relativeFilePath;

        if ($this->pathPrefix) {
            $filePath = $this->pathPrefix . '/' . $filePath;
        }

        return sprintf('%s/%s', $this->host, $filePath);
    }
}