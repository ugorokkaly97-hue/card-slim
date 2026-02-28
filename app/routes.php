<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\OrderController;

return function (App $app) {
    $app->get('/catalog', [HomeController::class, 'index']);
    $app->post('/catalog', [HomeController::class, 'index']);

    $app->group('/catalog', function (Group $group) {
        $group->get('/category/{id}', [ProductController::class, 'categoryInfo']);
        $group->post('/category/{id}', [ProductController::class, 'categoryInfo']);

        $group->get('/product/{id}', [ProductController::class, 'ProductInfo']);
        $group->post('/product/{id}', [ProductController::class, 'ProductInfo']);
    });

   $app->group('/order', function (Group $group) {
       $group->get('', [OrderController::class, 'index']);
       $group->post('', [OrderController::class, 'index']);

       $group->get('/{id}', [OrderController::class, 'orderForm']);
       $group->post('/{id}', [OrderController::class, 'createOrder']);

        $group->get('/update/{id}', [OrderController::class, 'updateForm']);
        $group->post('/update/{id}', [OrderController::class, 'updateOrder']);

        $group->post('/delete/{id}', [OrderController::class, 'deleteOrder']);
   });
};
