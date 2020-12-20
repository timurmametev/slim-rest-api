<?php

declare(strict_types=1);

namespace App\Api\v1\controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ConsumerController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('Hello!');

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}