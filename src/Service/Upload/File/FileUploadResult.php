<?php declare(strict_types=1);

namespace App\Service\Upload\File;

use App\Service\Upload\AbstractFileUploadResult;

class FileUploadResult extends AbstractFileUploadResult
{
    private const UPLOADED_ENTITY_TYPE = 'file';

    /** @inheritDoc */
    public function getType(): string
    {
        return self::UPLOADED_ENTITY_TYPE;
    }
}