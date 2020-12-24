<?php

declare(strict_types=1);

namespace App\Support;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Routing\RouteContext;
use Throwable;

class NotAllowedHandler implements ErrorHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private ResponseFactoryInterface $factory;

    /**
     * NotFoundHandler constructor.
     *
     * @param ResponseFactoryInterface $factory
     */
    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     * @param bool $displayErrorDetails
     * @param bool $logErrors
     * @param bool $logErrorDetails
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();

        $response = $this->factory->createResponse(405);
        $response->getBody()->write('Method must be one of: ' . implode(', ', $methods));

        return $response
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-type', 'text/html');
    }
}