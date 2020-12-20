<?php

/**
 * @var Container $container
 */

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use UltraLite\Container\Container;

$container = require_once __DIR__ . '/bootstrap.php';

$config = new PhpFile(__DIR__ . '/migrations.php');

return DependencyFactory::fromEntityManager(
    $config,
    new ExistingEntityManager($container->get(EntityManager::class))
);