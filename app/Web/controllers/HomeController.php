<?php

declare(strict_types=1);

namespace App\Web\controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('Hello!');

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}