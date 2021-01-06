<?php

declare(strict_types=1);

namespace App\Web\doctrine\repository;

use App\Web\doctrine\entity\Consumer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\UuidInterface;

class ConsumerRepository
{
    private EntityRepository $repository;
    private EntityManager $em;

    /**
     * ConsumerDataManager constructor.
     *
     * @param EntityRepository $repository
     * @param EntityManager $em
     */
    public function __construct(EntityRepository $repository, EntityManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @param UuidInterface $id
     * @return Consumer|object|null
     */
    public function get(UuidInterface $id): ?Consumer
    {
        return $this->repository->find($id->toString());
    }

    /**
     * @return Consumer[]
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param Consumer $consumer
     * @throws ORMException
     */
    public function add(Consumer $consumer): void
    {
        $this->em->persist($consumer);
        $this->em->flush();
    }

    /**
     * @param string $group
     * @return Consumer[]
     */
    public function getByGroupName(string $group): array
    {
        return $this->repository->findBy(['group' => $group]);
    }

    /**
     * @param Consumer $consumer
     * @throws ORMException
     */
    public function delete(Consumer $consumer): void
    {
        $this->em->remove($consumer);
        $this->em->flush();
    }
}