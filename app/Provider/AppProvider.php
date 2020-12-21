<?php

declare(strict_types=1);

namespace App\Provider;

use App\Middleware\CorsMiddleware;
use App\Support\Config;
use App\Support\NotFoundHandler;
use App\Support\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\CallableResolver;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Interfaces\RouteResolverInterface;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\RoutingMiddleware;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteCollector;
use Slim\Routing\RouteParser;
use Slim\Routing\RouteResolver;
use UltraLite\Container\Container;

class AppProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set(ResponseFactory::class, function () {
            return new ResponseFactory();
        });

        $container->set(ResponseFactoryInterface::class, function (ContainerInterface $container) {
            return $container->get(ResponseFactory::class);
        });

        $container->set(CallableResolver::class, function (ContainerInterface $container) {
            return new CallableResolver($container);
        });

        $container->set(CallableResolverInterface::class, function (ContainerInterface $container) {
            return $container->get(CallableResolver::class);
        });

        $container->set(RouteCollector::class, function (ContainerInterface $container) {
            return new RouteCollector(
                $container->get(ResponseFactoryInterface::class),
                $container->get(CallableResolverInterface::class),
                $container
            );
        });

        $container->set(RouteCollectorInterface::class, function (ContainerInterface $container) {
            return $container->get(RouteCollector::class);
        });

        $container->set(RouteResolver::class, function (ContainerInterface $container) {
            return new RouteResolver($container->get(RouteCollectorInterface::class));
        });

        $container->set(RouteResolverInterface::class, function (ContainerInterface $container) {
            return $container->get(RouteResolver::class);
        });

        $container->set(RouteParser::class, function (ContainerInterface $container) {
            return new RouteParser($container->get(RouteCollectorInterface::class));
        });

        $container->set(RouteParserInterface::class, function (ContainerInterface $container) {
            return $container->get(RouteParser::class);
        });

        $container->set(NotFoundHandler::class, function (ContainerInterface $container) {
            return new NotFoundHandler($container->get(ResponseFactoryInterface::class));
        });

        $container->set(ErrorMiddleware::class, function (ContainerInterface $container) {
            $middleware = new ErrorMiddleware(
                $container->get(CallableResolverInterface::class),
                $container->get(ResponseFactoryInterface::class),
                (boolean)$container->get(Config::class)->get('slim.debug'),
                true,
                true);
            $middleware->setErrorHandler(HttpNotFoundException::class, $container->get(NotFoundHandler::class));
            return $middleware;
        });

        $container->set(RoutingMiddleware::class, function (ContainerInterface $container) {
            return new RoutingMiddleware(
                $container->get(RouteResolverInterface::class),
                $container->get(RouteParserInterface::class)
            );
        });

        $container->set(CorsMiddleware::class, function (Container $container) {
            return new CorsMiddleware();
        });
    }
}