<?php

declare(strict_types=1);

namespace App\Provider;

use App\Api\v1\controllers\ConsumerController;
use App\Api\v1\repositories\ConsumerDataManager;
use App\Api\v1\services\ConsumerService;
use App\Middleware\NotAllowedMiddleware;
use App\Support\ServiceProviderInterface;
use App\Web\doctrine\repository\ConsumerRepository;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Routing\RouteCollectorProxy;
use UltraLite\Container\Container;

class ApiProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set(ConsumerDataManager::class, function (Container $container) {
            return new ConsumerDataManager($container->get(ConsumerRepository::class));
        });

        $container->set(ConsumerService::class, function (Container $container) {
            return new ConsumerService($container->get(ConsumerDataManager::class));
        });

        $container->set(ConsumerController::class, function (Container $container) {
            return new ConsumerController($container->get(ConsumerService::class));
        });

        $router = $container->get(RouteCollectorInterface::class);

        $router->group('/api/v1', function (RouteCollectorProxy $group) use ($router) {
            $group->get('/consumers/{id}', ConsumerController::class . ':identity');
            $group->delete('/consumers/{id}', ConsumerController::class . ':delete');
            $group->post('/consumers', ConsumerController::class . ':create');
            $group->get('/consumers', ConsumerController::class . ':group');
        });
    }
}