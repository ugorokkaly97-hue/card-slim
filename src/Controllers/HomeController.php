<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\CategoriesModel;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    private Twig $view;
    private CategoriesModel $categories;

    public function __construct(Twig $view, CategoriesModel $categories)
    {
        $this->view  = $view;
        $this->categories = $categories;
    }

    public function index(Request $request, Response $response): Response
    {
        $categories = $this->categories->getAll();

        return $this->view->render($response, 'index.twig', [
            'categories' => $categories,
        ]);
    }
}
