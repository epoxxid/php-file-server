<?php declare(strict_types=1);

namespace App\Service\Validator\Upload;

use App\Service\Validator\RequestValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractUploadedFileValidator
{
    private const CONFIG_EXTENSION_LIST_DELIMITER = ',';

    /** @var string[] List of allowed extensions */
    private $allowedExtensions = [];

    /** @var string[] List of forbidden extension */
    private $forbiddenExtensions = [];

    /**
     * @param string $fieldName
     * @param Request $request
     * @return UploadedFile
     */
    public function getValidatedUploadedFile(string $fieldName, Request $request): UploadedFile
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get($fieldName);

        if (!$uploadedFile) {
            throw new RequestValidationException(
                sprintf('Unable to find uploaded file at field name %s', $fieldName)
            );
        }

        $this->checkFileExtension($uploadedFile);

        return $uploadedFile;
    }

    /**
     * @param string $extensions
     */
    protected function setAllowedExtensions(string $extensions = null): void
    {
        if (null !== $extensions) {
            $this->allowedExtensions = explode(self::CONFIG_EXTENSION_LIST_DELIMITER, $extensions);
        }
    }

    /**
     * @param string $extensions
     */
    protected function setForbiddenExtensions(string $extensions = null): void
    {
        if (null !== $extensions) {
            $this->forbiddenExtensions = explode(self::CONFIG_EXTENSION_LIST_DELIMITER, $extensions);
        }
    }

    /**
     * Check whether uploaded file has one of allowed extensions
     * @param UploadedFile $file
     */
    private function checkFileExtension(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, $this->forbiddenExtensions, true)) {
            throw new RequestValidationException(sprintf(
                'Uploading files of type %s is forbidden',
                $extension
            ));
        }

        // Check file extension only if list of allowed types is set explicitly
        if (count($this->allowedExtensions) && !in_array($extension, $this->allowedExtensions, true)) {
            throw new RequestValidationException(sprintf(
                'Uploaded file of type %s is not in a list of allowed types',
                $extension
            ));
        }
    }
}
