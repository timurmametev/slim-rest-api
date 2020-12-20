<?php

declare(strict_types=1);

namespace App\Provider;

use App\Api\v1\controllers\ConsumerController;
use App\Support\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use UltraLite\Container\Container;

class ApiProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set(ConsumerController::class, function () {
            return new ConsumerController();
        });

        $router = $container->get(RouteCollectorInterface::class);

        $router->group('/api/v1/', function (RouteCollectorProxyInterface $router) {
            $router
                ->get('consumers', ConsumerController::class . ':index')
                ->setName('index');
        });
    }
}