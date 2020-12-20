<?php

declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$doctrine = require __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'doctrine.php';

use App\Provider\ApiProvider;
use App\Provider\WebProvider;
use App\Support\ServiceProviderInterface;
use App\Provider\AppProvider;
use App\Support\Config;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use UltraLite\Container\Container;

$env = getenv('APP_ENV');
if (!$env) $env = 'local';

$config = new Config(__DIR__ . DIRECTORY_SEPARATOR . 'config', $env, __DIR__);

$providers = [
    AppProvider::class,
    WebProvider::class,
    ApiProvider::class
];

$container = new Container([
    Config::class => function () use ($config) { return $config;},
]);

foreach ($providers as $className) {
    if (!class_exists($className)) {
        /** @noinspection PhpUnhandledExceptionInspection */
        throw new Exception('Provider ' . $className . ' not found');
    }
    $provider = new $className;
    if (!$provider instanceof ServiceProviderInterface) {
        /** @noinspection PhpUnhandledExceptionInspection */
        throw new Exception($className . ' has not provider');
    }
    $provider->register($container);
}

$container->set(EntityManager::class, function () use ($doctrine) {
    $config = Setup::createAnnotationMetadataConfiguration(
        $doctrine['config']['doctrine']['metadata_dirs'],
        $doctrine['config']['doctrine']['dev_mode']
    );

    $config->setMetadataDriverImpl(
        new AnnotationDriver(
            new AnnotationReader,
            $doctrine['config']['doctrine']['metadata_dirs']
        )
    );

    $config->setMetadataCacheImpl(
        new FilesystemCache(
            $doctrine['config']['doctrine']['cache_dir']
        )
    );

    return EntityManager::create(
        $doctrine['config']['doctrine']['connection'],
        $config
    );
});

return $container;