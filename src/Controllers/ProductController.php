<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\CategoriesModel;
use App\Models\ProductsModel;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductController
{
    private Twig $view;
    private ProductsModel $products;

    private CategoriesModel $categories;

    public function __construct(Twig $view, ProductsModel $products, CategoriesModel $categories)
    {
        $this->view  = $view;
        $this->products = $products;
        $this->categories = $categories;
    }

    public function categoryInfo(Request $request, Response $response, array $args): Response
    {
        $categoryId = (int)($args['id'] ?? 0);

        $categories = $this->categories->getById($categoryId);
        $products = $this->products->getByCategoryId($categoryId);

        return $this->view->render($response, 'category.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function ProductInfo(Request $request, Response $response, array $args): Response
    {
        $productId = (int)($args['id'] ?? 0);

        $product = $this->products->getById($productId);

        if (!$product) {
            return $response->withStatus(404);
        }

        return $this->view->render($response, 'product.twig', [
            'product' => $product
        ]);
    }
}