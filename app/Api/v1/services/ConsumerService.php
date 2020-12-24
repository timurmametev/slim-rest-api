<?php

declare(strict_types=1);

namespace App\Api\v1\services;

use App\Api\v1\dto\RequestParamsDTO;
use App\Api\v1\dto\ResponseDTO;
use App\Api\v1\dto\ValidateDTO;
use App\Api\v1\helpers\ResponseHelper;
use App\Api\v1\repositories\ConsumerDataManager;
use App\Web\common\collection\CollectionFactory;
use App\Web\common\collection\CollectionTemplate;
use Doctrine\ORM\ORMException;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;

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

        $collection = CollectionFactory::newCollection(
            CollectionFactory::getObjectFullName(
                new ValidateDTO()
            )
        );

        $collection->addData($this->repository->identityValidator($paramsDTO));

        if ($messages = $this->handleErrors($collection)) {
            return ResponseHelper::errorResponse($messages, 400);
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

        $collection = CollectionFactory::newCollection(
            CollectionFactory::getObjectFullName(
                new ValidateDTO()
            )
        );

        $collection->addData($this->repository->identityValidator($paramsDTO));
        $collection->addData($this->repository->groupValidator($paramsDTO));

        if ($messages = $this->handleErrors($collection)) {
            return ResponseHelper::errorResponse($messages, 400);
        }

        $consumer = $this->repository->getByIdentity($paramsDTO);

        if ($consumer->id) {
            return ResponseHelper::errorResponse([
                'parameter' => 'id',
                'message' => 'The consumer is already exist'
            ], 400);
        }

        try {
            $this->repository->createConsumer($paramsDTO);
        } catch (ORMException $exception) {
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
     * @param CollectionTemplate $collection
     * @return array
     */
    private function handleErrors(CollectionTemplate $collection): array
    {
        $messages = [];

        foreach ($collection->getData(false) as $item) {
            /** @var $item ValidateDTO */
            if ($item->messages) {
                $messages[] = $item->messages;
            }
        }

        return $messages;
    }
}