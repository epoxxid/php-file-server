<?php declare(strict_types=1);


namespace App\Tests\Functional\Action\Upload;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractUploadActionTest extends WebTestCase
{
    /** @var KernelBrowser  */
    protected $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    protected function buildAbsolutePath(string $relativePath): string
    {
        return sprintf(
            '%s/tests/Resources/files/%s',
            $this->client->getContainer()->getParameter('kernel.project_dir'),
            $relativePath
        );
    }

    protected function doUploadRequest(
        string $requestUri,
        string $requestParam,
        string $uploadedFilePath = null,
        string $mime = null
    ): Response
    {
        $files = [];

        if ($uploadedFilePath) {
            $files[$requestParam] = new UploadedFile(
                $uploadedFilePath,
                basename($uploadedFilePath),
                $mime ?: mime_content_type($uploadedFilePath)
            );
        }

        $this->client->request(Request::METHOD_POST, $requestUri, [], $files);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);
        return $response;
    }

}