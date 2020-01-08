<?php declare(strict_types=1);

namespace App\Service\Validator\Upload;

class UploadedImageValidator extends AbstractUploadedFileValidator
{
    public function __construct(string $allowedExtensions = null)
    {
        $this->setAllowedExtensions($allowedExtensions);
    }
}
