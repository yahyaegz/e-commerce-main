<?php

namespace App\Cart;

interface CartInterface
{
    public function add(int $productId, int $quantity = 1): void;

    public function remove(int $productId): void;

    public function clear(): void;

    /**
     * @return array<int, int>
     */
    public function getItems(): array;

    public function getQuantity(int $productId): int;
}
