<?php

namespace App\Models;

use ORM;

class OrdersModel
{
    public function getAll(): array
    {
        $arrays = ORM::for_table('orders')
            ->select('orders.*')
            ->select('products.name', 'product_name')
            ->select('products.id', 'product_id')
            ->join('products', 'orders.product_id = products.id')
            ->find_many();

        $orders = [];
        foreach ($arrays as $array) {
            $orders[] = $array->as_array();
        }
        return $orders;
    }

    public function getByProductID(int $productID): array
    {
        $order = ORM::for_table('orders')
            ->select('orders.*')
            ->select('products.name', 'product_name')
            ->join('products', 'orders.product_id = products.id')
            ->where('orders.product_id', $productID)
            ->order_by_desc('orders.id')
            ->find_one();

        if (!$order) {
            return [];
        }

        return $order->as_array();
    }

    public function getById(int $orderId): ?array
    {
        $order = ORM::for_table('orders')
            ->select('orders.*')
            ->select('products.name', 'product_name')
            ->join('products', 'orders.product_id = products.id')
            ->where('orders.id', $orderId)
            ->find_one();

        if (!$order) {
            return null;
        }

        return $order->as_array();
    }

    public function create(int $product_id, string $address)
    {
        $order = ORM::for_table('orders')->create();
        $order->product_id = $product_id;
        $order->address = $address;
        $order->status = 'open';
        $order->save();
    }

    public function update(int $id, string $status): bool
    {
        $order = ORM::for_table('orders')
            ->where('id', $id)
            ->find_one();

        if (!$order) {
            return false;
        }

        $order->set('status', $status);

        return $order->save();
    }

    public function delete(int $id)
    {
        $order = ORM::for_table('orders')
            ->where('id', $id)
            ->find_one();

        $order->delete();

        return true;
    }

}