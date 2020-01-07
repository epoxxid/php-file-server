<?php declare(strict_types=1);

namespace App\Action\Upload;

use App\Service\Responder\SerializerResponder;
use App\Service\Upload\Video\VideoUploader;
use App\Service\Validator\Upload\UploadedFileValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class UploadVideoAction
{
    private const UPLOADED_FILE_PARAM = 'video';

    /** @var UploadedFileValidator */
    private $validator;

    /** @var VideoUploader */
    private $uploader;

    /** @var SerializerResponder */
    private $responder;

    public function __construct(
        UploadedFileValidator $validator,
        VideoUploader $uploader,
        SerializerResponder $responder
    )
    {
        $this->validator = $validator;
        $this->uploader = $uploader;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $file = $this->validator->getValidatedUploadedFile(self::UPLOADED_FILE_PARAM, $request);
            $result = $this->uploader->upload($file);
            return $this->responder->createSuccessResponse($result);
        } catch (Throwable $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}
