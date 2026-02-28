<?php
declare(strict_types=1);

namespace App\Models;

use ORM;

class ProductsModel
{
    public function getAll(): array
    {
        $products = ORM::for_table('products')->find_many();

        return $products;
    }

    public function getByCategoryId(int $categoryId): array
    {
        $products = ORM::for_table('products')
            ->select('products.*')
            ->select('products.name', 'product_name')
            ->select('categories.name', 'category_name')
            ->join('categories', 'products.category_id = categories.id')
            ->where('products.category_id', $categoryId)
            ->find_many();

        return $products;
    }

    public function getById(int $productId): array
    {
        $product = ORM::for_table('products')
            ->where('id', $productId)
            ->find_one();

        return $product->as_array();
    }
}