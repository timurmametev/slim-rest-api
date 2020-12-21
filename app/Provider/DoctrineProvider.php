<?php

declare(strict_types=1);

namespace App\Provider;

use App\Support\ServiceProviderInterface;
use App\Web\doctrine\entity\Consumer;
use App\Web\doctrine\repository\ConsumerRepository;
use Doctrine\ORM\EntityManager;
use UltraLite\Container\Container;

class DoctrineProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set(ConsumerRepository::class, function (Container $container) {
            $em = $container->get(EntityManager::class);

            return new ConsumerRepository(
                $em->getRepository(Consumer::class),
                $em
            );
        });
    }
}