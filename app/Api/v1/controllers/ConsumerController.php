<?php

declare(strict_types=1);

namespace App\Api\v1\controllers;

use App\Api\v1\helpers\ResponseHelper;
use App\Api\v1\services\ConsumerService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;

class ConsumerController
{
    private ConsumerService $service;

    /**
     * ConsumerController constructor.
     *
     * @param ConsumerService $service
     */
    public function __construct(ConsumerService $service)
    {
        $this->service = $service;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function identity(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $responseDTO = $this->service->getConsumerByIdentity($args);
        return ResponseHelper::successResponse($response, $responseDTO);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function create(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $responseDTO = $this->service->createConsumer($request->getParsedBody());
        return ResponseHelper::successResponse($response, $responseDTO);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function group(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        $responseDTO = $this->service->getConsumers($request->getQueryParams());
        return ResponseHelper::successResponse($response, $responseDTO);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws ReflectionException
     */
    public function delete(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $responseDTO = $this->service->deleteConsumer($args);
        return ResponseHelper::successResponse($response, $responseDTO);
    }
}