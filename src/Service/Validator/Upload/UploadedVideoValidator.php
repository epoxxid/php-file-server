<?php declare(strict_types=1);

namespace App\Service\Validator\Upload;

class UploadedVideoValidator extends AbstractUploadedFileValidator
{
    public function __construct(string $allowedExtensions = null)
    {
        $this->setAllowedExtensions($allowedExtensions);
    }
}
