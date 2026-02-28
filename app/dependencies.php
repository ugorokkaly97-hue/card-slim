<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Controllers\OrderController;
use App\Controllers\ProductController;
use App\Models\CategoriesModel;
use App\Models\ProductsModel;
use App\Models\OrdersModel;
use App\Controllers\HomeController;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        // Логгер
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $loggerSettings = $settings->get('logger');

            $logger = new Logger($loggerSettings['name']);
            $logger->pushProcessor(new UidProcessor());
            $logger->pushHandler(new StreamHandler(
                $loggerSettings['path'],
                $loggerSettings['level']
            ));

            return $logger;
        },

        // Twig
        Twig::class => function () {
            return Twig::create(__DIR__ . '/../templates', [
                'cache' => false,
            ]);
        },

        // Модель

        ProductsModel::class => function (ContainerInterface $c) {
            return new ProductsModel();
        },

        CategoriesModel::class => function (ContainerInterface $c) {
            return new CategoriesModel();
        },

        OrdersModel::class => function (ContainerInterface $c) {
            return new OrdersModel();
        },


        // Контроллеры
        HomeController::class => function (ContainerInterface $c) {
            return new HomeController(
                $c->get(Twig::class),
                $c->get(CategoriesModel::class),
            );
        },

        ProductController::class => function (ContainerInterface $c) {
        return new ProductController(
                $c->get(Twig::class),
                $c->get(ProductsModel::class),
                $c->get(CategoriesModel::class),
            );
        },

        OrderController::class => function (ContainerInterface $c) {
        return new OrderController(
               $c->get(Twig::class),
               $c->get(ProductsModel::class),
               $c->get(OrdersModel::class),
            );
        }
    ]);
};
