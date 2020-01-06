<?php declare(strict_types=1);

namespace App\Tests\Functional\Action\Upload;

use Symfony\Component\HttpFoundation\Response;

class UploadImageActionTest extends AbstractUploadActionTest
{
    private const UPLOAD_ENDPOINT_URI = '/api/v1/upload/image';
    private const REQUEST_PARAM = 'image';

    /**
     * @param string $filePath
     * @dataProvider validFilesProvider
     */
    public function testItCanUploadValidFile(string $filePath): void
    {
        $absolutePath = $this->buildAbsolutePath($filePath);
        $response = $this->doUploadRequest(
            self::UPLOAD_ENDPOINT_URI,
            self::REQUEST_PARAM,
            $absolutePath
        );
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode(), $filePath);

        $responseBody = json_decode($response->getContent(), true);

        // ===============================>
        echo "<pre>";
        echo "<span style=\"color:red\"> ============================== </span>\n";
        var_dump($responseBody);
        echo 'Variable dump from <b style="color:blue">' . __FILE__ . '</b> @ line #' . __LINE__;
        echo "\n<span style=\"color:red\"> ============================== </span>\n";
        echo "</pre>";
        // ===============================>

        $this->assertNotEmpty($responseBody['originalImageUri'], 'has original image uri');
        $this->assertNotEmpty($responseBody['thumbnailUri'], 'has thumbnail uri');
        $this->assertNotEmpty($responseBody['fileUri'], 'has file uri');
        $this->assertNotEmpty($responseBody['fileId'], 'has file id');
        $this->assertNotEmpty($responseBody['fileName'], 'has file name');
    }

    /**
     * @param string $filePath
     * @dataProvider invalidFilesProvider
     */
    public function testItFailsOnAttemptToUploadNonImage(string $filePath): void
    {
        $absolutePath = $this->buildAbsolutePath($filePath);
        $response = $this->doUploadRequest(
            self::UPLOAD_ENDPOINT_URI,
            self::REQUEST_PARAM,
            $absolutePath
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode(), $filePath);
    }

    public function validFilesProvider(): array
    {
        return [
            ['images/image.jpg', '`jpg` image'],
            ['images/image.jpeg', '`jpeg` image'],
            ['images/image.gif', '`gif` image'],
            ['images/image.png', '`png` image'],
        ];
    }

    public function invalidFilesProvider(): array
    {
        return [
            ['docs/document.csv'],
            ['docs/document.doc'],
            ['docs/document.docx'],
            ['docs/document.html'],
            ['docs/document.odp'],
            ['docs/document.odt'],
            ['docs/document.pdf'],
            ['docs/document.ppt'],
            ['docs/document.rtf'],
            ['docs/document.xls'],
            ['docs/document.xlsx'],
            ['video/video.avi'],
            ['video/video.mov'],
            ['video/video.mp4'],
            ['video/video.ogg'],
            ['video/video.wmv'],
        ];
    }
}