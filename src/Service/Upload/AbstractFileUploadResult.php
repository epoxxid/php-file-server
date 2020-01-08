<?php declare(strict_types=1);


namespace App\Service\Upload;

abstract class AbstractFileUploadResult
{
    /** @var string */
    protected $fileId;

    /** @var string */
    protected $fileUri;

    /** @var string */
    protected $fileName;

    public function __construct(string $fileId, string $fileName, string $videoFileUri)
    {
        $this->fileId = $fileId;
        $this->fileName = $fileName;
        $this->fileUri = $videoFileUri;
    }

    /**
     * Returns general type of uploaded entity
     * @return string
     */
    abstract public function getType(): string;


    /** @return string */
    public function getFileId(): string
    {
        return $this->fileId;
    }

    /** @return string */
    public function getOriginalFileName(): string
    {
        return $this->fileName;
    }

    /** @return string */
    public function getFileUri(): string
    {
        return $this->fileUri;
    }
}
