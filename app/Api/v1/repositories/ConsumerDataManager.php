<?php

declare(strict_types=1);

namespace App\Api\v1\repositories;

use App\Api\v1\dto\RequestParamsDTO;
use App\Api\v1\dto\ValidateErrorDTO;
use App\Web\common\collection\CollectionFactory;
use App\Web\common\collection\CollectionTemplate;
use App\Web\doctrine\entity\Consumer;
use App\Web\doctrine\repository\ConsumerRepository;
use App\Web\dto\ConsumerDTO;
use Doctrine\ORM\ORMException;
use Exception;
use ReflectionException;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConsumerDataManager
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var ConsumerRepository
     */
    private ConsumerRepository $repository;

    /**
     * ConsumerDataManager constructor.
     *
     * @param ConsumerRepository $repository
     */
    public function __construct(ConsumerRepository $repository)
    {
        $this->validator = Validation::createValidator();
        $this->repository = $repository;
    }

    /**
     * @return CollectionTemplate
     * @throws ReflectionException
     * @throws Exception
     */
    public function getAll(): CollectionTemplate
    {
        $consumers = $this->repository->getAll();

        $collection = CollectionFactory::newCollection(
            CollectionFactory::getObjectFullName(
                new ConsumerDTO()
            )
        );

        foreach ($consumers as $consumer) {
            $collection->addData([
                'id'    => $consumer->getId(),
                'group' => $consumer->getGroup()
            ]);
        }

        return $collection;
    }

    /**
     * @param RequestParamsDTO $paramsDTO
     * @return ConsumerDTO
     */
    public function getByIdentity(RequestParamsDTO $paramsDTO): ConsumerDTO
    {
        $consumer = $this->repository->get($paramsDTO->id);

        if (!$consumer) {
            return new ConsumerDTO();
        }

        return new ConsumerDTO([
            'id'    => $consumer->getId(),
            'group' => $consumer->getGroup()
        ]);
    }

    /**
     * @param RequestParamsDTO $paramsDTO
     * @return CollectionTemplate
     * @throws ReflectionException
     * @throws Exception
     */
    public function getByGroup(RequestParamsDTO $paramsDTO): CollectionTemplate
    {
        $consumers = $this->repository->getByGroupName($paramsDTO->group);

        $collection = CollectionFactory::newCollection(
            CollectionFactory::getObjectFullName(
                new ConsumerDTO()
            )
        );

        foreach ($consumers as $consumer) {
            $collection->addData([
                'id'    => $consumer->getId(),
                'group' => $consumer->getGroup()
            ]);
        }

        return $collection;
    }

    /**
     * @param RequestParamsDTO $paramsDTO
     * @throws ORMException
     */
    public function createEntry(RequestParamsDTO $paramsDTO): void
    {
        $consumer = new Consumer();
        $consumer->setId($paramsDTO->id);
        $consumer->setGroup($paramsDTO->group);
        $this->repository->add($consumer);
    }

    /**
     * @param RequestParamsDTO $paramsDTO
     * @throws ORMException
     */
    public function deleteEntry(RequestParamsDTO $paramsDTO): void
    {
        $this->repository->delete(
            $this->repository->get($paramsDTO->id)
        );
    }

    /**
     * @param RequestParamsDTO $paramsDTO
     * @return array
     * @throws ReflectionException
     */
    public function identityValidator(RequestParamsDTO $paramsDTO): array
    {
        $errors = $this->validator->validate($paramsDTO->id, [
            new NotBlank(),
            new Uuid()
        ]);

        return $this->processErrors($errors, 'id');
    }

    /**
     * @param RequestParamsDTO $paramsDTO
     * @return array
     * @throws ReflectionException
     */
    public function groupValidator(RequestParamsDTO $paramsDTO): array
    {
        $errors = $this->validator->validate($paramsDTO->group, [
            new NotBlank(),
            new Length([
                'min' => 1,
                'max' => 50
            ]),
            new Regex([
                'pattern' => '/^[-a-zA-Z0-9_]+$/'
            ])
        ]);

        return $this->processErrors($errors, 'group');
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @param string $parameter
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
    private function processErrors(ConstraintViolationListInterface $errors, string $parameter): array
    {
        $collection = CollectionFactory::newCollection(
            CollectionFactory::getObjectFullName(
                new ValidateErrorDTO()
            )
        );

        if ($errors->count()) {
            foreach ($errors as $error) {
                /** @var $error ConstraintViolation */
                $collection->addData([
                    'parameter' => $parameter,
                    'message' => $error->getMessage()
                ]);
            }
        }

        return $collection->getData();
    }
}