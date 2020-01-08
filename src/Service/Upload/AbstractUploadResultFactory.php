<?php declare(strict_types=1);

namespace App\Service\Upload;

abstract class AbstractUploadResultFactory
{
    /** @var string */
    protected $serverHostName;

    public function __construct(string $serverHostName)
    {
        $this->serverHostName = $serverHostName;
    }

    /**
     * Build file type specific URI
     * @param string $filePath
     * @return string
     */
    protected function buildUri(string $filePath): string
    {
        return sprintf('%s/%s', $this->serverHostName, $filePath);
    }
}
