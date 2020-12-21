<?php

declare(strict_types=1);

namespace App\Web\doctrine\repository;

use App\Web\doctrine\entity\Consumer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\Uuid;

class ConsumerRepository
{
    private EntityRepository $repository;
    private EntityManager $em;

    /**
     * ConsumerRepository constructor.
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
     * @param Uuid $id
     * @return Consumer|object|null
     */
    public function get(Uuid $id): ?Consumer
    {
        return $this->repository->find($id->toString());
    }

    /**
     * @param Consumer $consumer
     * @throws ORMException
     */
    public function add(Consumer $consumer): void
    {
        $this->em->persist($consumer);
    }

    /**
     * @param string $group
     * @return Consumer[]
     */
    public function getByGroupName(string $group): array
    {
        return $this->repository->findBy(['group' => $group]);
    }
}