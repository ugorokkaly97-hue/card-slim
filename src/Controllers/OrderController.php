<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\OrdersModel;
use App\Models\ProductsModel;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class OrderController
{
    private Twig $view;
    private ProductsModel $product;

    private OrdersModel $order;

    public function __construct(Twig $view, ProductsModel $product, OrdersModel $order)
    {
        $this->view  = $view;
        $this->product = $product;
        $this->order  = $order;
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $orders = $this->order->getAll();

        return $this->view->render($response, 'ordersIndex.twig', [
            'orders' => $orders
        ]);
    }
    public function orderForm(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);

        $product = $this->product->getById($id);

        return $this->view->render($response, 'order.twig', [
            'product' => $product
        ]);
    }

    public function createOrder(Request $request, Response $response, array $args): Response
    {
        $product_id = (int)($args['id'] ?? 0);

        $data = (array)$request->getParsedBody();
        $address = $data['address'] ?? '';

        $product = $this->product->getById($product_id);

        $this->order->create($product_id, $address);

        return $response->withHeader('Location', '/catalog')
            ->withStatus(302);
    }

    public function updateForm(Request $request, Response $response, array $args): Response
    {
        $orderId = (int)($args['id'] ?? 0);

        $order = $this->order->getById($orderId);

        return $this->view->render($response, 'update.twig', [
            'order' => $order
        ]);
    }

    public function updateOrder(Request $request, Response $response, array $args): Response
    {
        $orderId = (int)($args['id'] ?? 0);

        $data = (array)$request->getParsedBody();
        $status = $data['status'] ?? '';

        $result = $this->order->update($orderId, $status);

        if (!$result) {
            $_SESSION['error'] = 'Не удалось обновить заказ';
            return $response->withHeader('Location', '/order/update/' . $orderId)->withStatus(302);
        }

        $_SESSION['success'] = 'Заказ успешно обновлен';
        return $response->withHeader('Location', '/order')->withStatus(302);
    }

    public function deleteOrder(Request $request, Response $response, $args): Response
    {
        $id = (int)($args['id'] ?? 0);

        if ($id <= 0) {
            return $response->withHeader('Location', '/catalog')
                ->withStatus(302);
        }

        $this->order->delete($id);

        return $response->withHeader('Location', '/catalog')
            ->withStatus(302);
    }
}