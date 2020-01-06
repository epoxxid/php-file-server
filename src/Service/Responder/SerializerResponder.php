<?php declare(strict_types=1);
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2019 (original work) Open Assessment Technologies SA;
 *
 * @author Roman Kovalev <roman.kovalev@taotesting.com>
 */

namespace App\Service\Responder;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class SerializerResponder
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var bool */
    private $applicationDebug;

    public function __construct(SerializerInterface $serializer, bool $applicationDebug = false)
    {
        $this->serializer = $serializer;
        $this->applicationDebug = $applicationDebug;
    }

    /** @inheritDoc */
    public function createSuccessResponse(
        $data,
        int $statusCode = Response::HTTP_OK,
        array $headers = [],
        array $serializerContext = []
    ): Response
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($data, 'json', $serializerContext),
            $statusCode,
            $headers
        );
    }

    /** @inheritDoc */
    public function createErrorResponse(
        Throwable $e,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $headers = [],
        array $serializerContext = []
    ): Response
    {
        $data = ['errorMessage' => $e->getMessage()];

        if ($this->applicationDebug) {
            $data['trace'] = $e->getTrace();
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($data, 'json', $serializerContext),
            $e instanceof HttpExceptionInterface ? $e->getStatusCode() : $statusCode,
            $e instanceof HttpExceptionInterface ? $e->getHeaders() : $headers
        );
    }
}
