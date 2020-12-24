<?php

declare(strict_types=1);

namespace App\Api\v1\repositories;

use App\Api\v1\dto\RequestParamsDTO;
use App\Api\v1\dto\ValidateDTO;
use App\Web\doctrine\entity\Consumer;
use App\Web\doctrine\repository\ConsumerRepository;
use App\Web\dto\ConsumerDTO;
use Doctrine\ORM\ORMException;
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
     * @throws ORMException
     */
    public function createConsumer(RequestParamsDTO $paramsDTO)
    {
        $consumer = new Consumer();
        $consumer->setId($paramsDTO->id);
        $consumer->setGroup($paramsDTO->group);
        $this->repository->add($consumer);
    }

    /**
     * @param RequestParamsDTO $paramsDTO
     * @return array
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
     */
    private function processErrors(ConstraintViolationListInterface $errors, string $parameter): array
    {
        $messages = [];

        if ($errors->count()) {
            foreach ($errors as $error) {
                /** @var $error ConstraintViolation */
                $messages[] = [
                    'parameter' => $parameter,
                    'message' => $error->getMessage()
                ];
            }
        }

        return [
            'messages' => $messages
        ];
    }
}