<?php

namespace App\Models;

use ORM;

class CategoriesModel
{
    public function getAll(): array
    {
        $categories = ORM::for_table('categories')->find_many();
        return $categories;
    }

    public function getById(int $categoryId): array
    {
        $category = ORM::for_table('categories')->where('id', $categoryId)->find_one();
        return $category->as_array();
    }

}