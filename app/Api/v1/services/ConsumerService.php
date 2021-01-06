<?php

declare(strict_types=1);

namespace App\Api\v1\services;

use App\Api\v1\dto\RequestParamsDTO;
use App\Api\v1\dto\ResponseDTO;
use App\Api\v1\dto\ValidateErrorDTO;
use App\Api\v1\helpers\ResponseHelper;
use App\Api\v1\repositories\ConsumerDataManager;
use App\Web\common\collection\CollectionFactory;
use Exception;
use ReflectionException;
use Throwable;

class ConsumerService
{
    private ConsumerDataManager $repository;

    /**
     * ConsumerService constructor.
     *
     * @param ConsumerDataManager $repository
     */
    public function __construct(ConsumerDataManager $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $args
     * @return ResponseDTO
     * @throws ReflectionException
     * @throws Exception
     */
    public function getConsumerByIdentity(array $args): ResponseDTO
    {
        $paramsDTO = new RequestParamsDTO($args);

        $validation = $this->paramsValidation($paramsDTO);

        if ($validation) {
            return ResponseHelper::errorResponse($validation, 400);
        }

        $consumer = $this->repository->getByIdentity($paramsDTO);

        if (!$consumer->id) {
            return ResponseHelper::notFoundResponse();
        }

        return new ResponseDTO([
            'code' => 200,
            'response' => $consumer->getData()
        ]);
    }

    /**
     * @param array $params
     * @return ResponseDTO
     * @throws ReflectionException
     */
    public function getConsumers(array $params): ResponseDTO
    {
        if (empty($params)) {
            return $this->getAllConsumers();
        }

        return $this->getConsumersByGroup($params);
    }

    /**
     * @return ResponseDTO
     * @throws ReflectionException
     */
    public function getAllConsumers(): ResponseDTO
    {
        $consumers = $this->repository->getAll();

        return new ResponseDTO([
            'code' => 200,
            'response' => $consumers->getData()
        ]);
    }

    /**
     * @param array $params
     * @return ResponseDTO
     * @throws ReflectionException
     * @throws Exception
     */
    public function getConsumersByGroup(array $params): ResponseDTO
    {
        $paramsDTO = new RequestParamsDTO($params);

        if (!$paramsDTO->group) {
            return ResponseHelper::errorResponse(['Parameter group is empty'], 400);
        }

        $validation = $this->paramsValidation($paramsDTO);

        if ($validation) {
            return ResponseHelper::errorResponse($validation, 400);
        }

        $consumers = $this->repository->getByGroup($paramsDTO);

        return new ResponseDTO([
            'code' => 200,
            'response' => $consumers->getData()
        ]);
    }

    /**
     * @param array|null $params
     * @return ResponseDTO
     * @throws ReflectionException
     * @throws Exception
     */
    public function createConsumer(?array $params): ResponseDTO
    {
        if (!is_array($params) || empty($params)) {
            return ResponseHelper::errorResponse(['Request params is empty'], 400);
        }

        $paramsDTO = new RequestParamsDTO($params);

        $validation = $this->paramsValidation($paramsDTO);

        if ($validation) {
            return ResponseHelper::errorResponse($validation, 400);
        }

        $consumer = $this->repository->getByIdentity($paramsDTO);

        if ($consumer->id) {
            return ResponseHelper::errorResponse([
                'parameter' => 'id',
                'message' => 'The consumer is already exist'
            ], 400);
        }

        try {
            $this->repository->createEntry($paramsDTO);
        } catch (Throwable $exception) {
            return new ResponseDTO([
                'code' => 500,
                'response' => $exception->getMessage()
            ]);
        }

        return new ResponseDTO([
            'code' => 201,
            'response' => []
        ]);
    }

    /**
     * @param array $args
     * @return ResponseDTO
     * @throws ReflectionException
     * @throws Exception
     */
    public function deleteConsumer(array $args): ResponseDTO
    {
        $paramsDTO = new RequestParamsDTO($args);

        $validation = $this->paramsValidation($paramsDTO);

        if ($validation) {
            return ResponseHelper::errorResponse($validation, 400);
        }

        $consumer = $this->repository->getByIdentity($paramsDTO);

        if (!$consumer->id) {
            return ResponseHelper::notFoundResponse();
        }

        try {
            $this->repository->deleteEntry($paramsDTO);
        } catch (Throwable $exception) {
            return new ResponseDTO([
                'code' => 500,
                'response' => $exception->getMessage()
            ]);
        }

        return new ResponseDTO([
            'code' => 200,
            'response' => []
        ]);
    }

    /**
     * @param RequestParamsDTO $paramsDTO
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
    private function paramsValidation(RequestParamsDTO $paramsDTO): array
    {
        $collection = CollectionFactory::newCollection(
            CollectionFactory::getObjectFullName(
                new ValidateErrorDTO()
            )
        );

        if ($paramsDTO->id) {
            $collection->addData(
                $this->repository->identityValidator($paramsDTO),
                true
            );
        }

        if ($paramsDTO->group) {
            $collection->addData(
                $this->repository->groupValidator($paramsDTO),
                true
            );
        }

        return $collection->getData();
    }
}