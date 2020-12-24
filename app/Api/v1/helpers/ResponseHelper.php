<?php

declare(strict_types=1);

namespace App\Api\v1\helpers;

use App\Api\v1\dto\ResponseDTO;
use Psr\Http\Message\ResponseInterface;

class ResponseHelper
{
    /**
     * @param ResponseInterface $response
     * @param ResponseDTO $responseDTO
     * @return ResponseInterface
     */
    public static function successResponse(ResponseInterface $response, ResponseDTO $responseDTO): ResponseInterface
    {
        $response->getBody()->write(json_encode($responseDTO->response));

        return $response
            ->withStatus($responseDTO->code)
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param array $errors
     * @param int $code
     * @return ResponseDTO
     */
    public static function errorResponse(array $errors, int $code): ResponseDTO
    {
        return new ResponseDTO([
            'code' => $code,
            'response' => [
                'errors' => $errors
            ]
        ]);
    }

    /**
     * @return ResponseDTO
     */
    public static function notFoundResponse(): ResponseDTO
    {
        return new ResponseDTO([
            'code' => 404,
            'response' => [
                'message' => 'Object is Not Found'
            ]
        ]);
    }
}