<?php declare(strict_types=1);


namespace App\Action\Upload;

use App\Service\Responder\SerializerResponder;
use App\Service\Upload\Image\ImageUploader;
use App\Service\Validator\Upload\UploadedImageValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class UploadImageAction
{
    private const UPLOADED_FILE_PARAM = 'image';

    /** @var UploadedImageValidator */
    private $validator;

    /** @var ImageUploader */
    private $uploader;

    /** @var SerializerResponder */
    private $responder;

    public function __construct(
        UploadedImageValidator $validator,
        ImageUploader $fileUploader,
        SerializerResponder $responder
    )
    {
        $this->validator = $validator;
        $this->uploader = $fileUploader;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $image = $this->validator->getValidatedUploadedFile(self::UPLOADED_FILE_PARAM, $request);
            $result = $this->uploader->upload($image);
            return $this->responder->createSuccessResponse($result);
        } catch (Throwable $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }
}