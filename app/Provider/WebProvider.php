<?php

declare(strict_types=1);

namespace App\Provider;

use App\Support\ServiceProviderInterface;
use App\Web\controllers\HomeController;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use UltraLite\Container\Container;

class WebProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set(HomeController::class, function () {
            return new HomeController();
        });

        $router = $container->get(RouteCollectorInterface::class);

        $router->group('/', function (RouteCollectorProxyInterface $router) {
            $router
                ->get('', HomeController::class . ':index')
                ->setName('index');
        });
    }
}