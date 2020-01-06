<?php declare(strict_types=1);


namespace App\Service\Validator\Upload;

use App\Service\Validator\RequestValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploadedFileValidator
{
    /** @var array */
    private $forbiddenExtensions = [];

    /** @var array */
    private $allowedExtensions = [];

    public function __construct(string $forbiddenExtensions, string $allowedExtensions = null)
    {
        $this->forbiddenExtensions = explode(',', $forbiddenExtensions);

        if ($allowedExtensions) {
            $this->allowedExtensions = explode(',', $allowedExtensions);
        }
    }

    public function getValidatedUploadedFile(string $fieldName, Request $request): UploadedFile
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get($fieldName);

        if (!$uploadedFile) {
            throw new RequestValidationException(
                sprintf('Unable to find uploaded file at field name %s', $fieldName)
            );
        }

        if (!$this->checkFileExtension($uploadedFile)) {
            throw new RequestValidationException('Uploaded file has an invalid extension');
        }

        return $uploadedFile;
    }

    /**
     * Check whether uploaded file has one of allowed extensions
     * @param UploadedFile $file
     * @return bool
     */
    private function checkFileExtension(UploadedFile $file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, $this->forbiddenExtensions, true)) {
            return false;
        }

        // Check file extension only if list of allowed types is set explicitly
        if ($this->allowedExtensions && !in_array($extension, $this->allowedExtensions, true)) {
            return false;
        }

        return true;
    }
}