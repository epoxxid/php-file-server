<?php declare(strict_types=1);


namespace App\Action\Download;

use App\Service\Upload\FileStoreService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class DownloadFileAction
{
    private const FILE_PATH_PARAM = 'path';

    /** @var LoggerInterface */
    private $logger;

    /** @var FileStoreService */
    private $storeService;

    public function __construct(FileStoreService $storeService, LoggerInterface $logger)
    {
        $this->storeService = $storeService;
        $this->logger = $logger;
    }

    public function __invoke(Request $request): Response
    {
        $filePath = $request->get(self::FILE_PATH_PARAM);

        if (empty($filePath)) {
            // TODO: Maybe check path for valid format
            throw new BadRequestHttpException('File path is not specified');
        }

        try {
            return $this->createResponse(
                $this->storeService->getMimeType($filePath),
                $this->storeService->readStoredFileAsStream($filePath)
            );
        } catch (Throwable $e) {
            $this->logger->error("File by path $filePath is not found");
            throw new NotFoundHttpException('File does not exist');
        }
    }

    /**
     * @param string $mimeType
     * @param $fileStream
     * @return StreamedResponse
     */
    private function createResponse(string $mimeType, $fileStream): StreamedResponse
    {
        return new StreamedResponse(
            static function () use ($fileStream) {
                $outputStream = fopen('php://output', 'wb');
                stream_copy_to_stream($fileStream, $outputStream);
            },
            Response::HTTP_OK,
            ['Content-Type', $mimeType]
        );
    }
}
