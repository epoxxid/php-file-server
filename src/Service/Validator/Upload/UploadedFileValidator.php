<?php declare(strict_types=1);

namespace App\Service\Validator\Upload;

class UploadedFileValidator extends AbstractUploadedFileValidator
{
    public function __construct(
        string $forbiddenExtensions = null,
        string $allowedExtensions = null
    )
    {
        $this->setForbiddenExtensions($forbiddenExtensions);
        $this->setAllowedExtensions($allowedExtensions);
    }
}
