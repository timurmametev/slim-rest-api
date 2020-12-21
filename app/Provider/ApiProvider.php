<?php

declare(strict_types=1);

namespace App\Provider;

use App\Api\v1\controllers\ConsumerController;
use App\Support\ServiceProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Routing\RouteCollectorProxy;
use UltraLite\Container\Container;

class ApiProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set(ConsumerController::class, function () {
            return new ConsumerController();
        });

        $router = $container->get(RouteCollectorInterface::class);

        $router->group('/api/v1', function (RouteCollectorProxy $group) {
            /*$router
                ->get('consumers', ConsumerController::class . ':index')
                ->setName('index');*/

            $group->get('/consumers', function (
                ServerRequestInterface $request,
                ResponseInterface $response,
                array $args
            ): ResponseInterface {
                $response->getBody()->write('List all consumers');
                return $response;
            });

            $group->post('/consumers', function (
                ServerRequestInterface $request,
                ResponseInterface $response
            ): ResponseInterface {
                $data = (array)$request->getParsedBody();

                $response->getBody()->write('Create user');

                return $response;
            });

            $group->get('/consumers/{id}', function (
                ServerRequestInterface $request,
                ResponseInterface $response,
                array $args
            ): ResponseInterface {
                $userId = (int)$args['id'];
                $response->getBody()->write(sprintf('Get consumers: %s', $userId));

                return $response;
            });
        });
    }
}