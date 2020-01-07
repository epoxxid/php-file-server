<?php declare(strict_types=1);


namespace App\Action\Download;

use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class DownloadImageAction
{
    private const IMAGE_PATH_PARAM = 'path';

    /** @var FilesystemInterface */
    private $imageStorage;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        FilesystemInterface $imageStorage,
        LoggerInterface $logger
    )
    {
        $this->imageStorage = $imageStorage;
        $this->logger = $logger;
    }

    public function __invoke(Request $request)
    {
        $imagePath = $request->get(self::IMAGE_PATH_PARAM);

        if (empty($imagePath)) {
            // TODO: Maybe check path for valid format
            throw new BadRequestHttpException('Image path is not specified');
        }

        try {
            $fileContents = $this->imageStorage->read($imagePath);
            return new BinaryFileResponse($fileContents);
        } catch (Throwable $e) {
            $this->logger->error(sprintf(
                'Image file by path %s is not found',
                $imagePath
            ));
            throw new NotFoundHttpException('File does not exist');
        }
    }
}
