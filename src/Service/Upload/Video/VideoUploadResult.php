<?php declare(strict_types=1);

namespace App\Service\Upload\Video;

use App\Service\Upload\AbstractFileUploadResult;

class VideoUploadResult extends AbstractFileUploadResult
{
    private const UPLOADED_ENTITY_TYPE = 'video';

    /** @inheritDoc */
    public function getType(): string
    {
        return self::UPLOADED_ENTITY_TYPE;
    }
}
