<?php

namespace App\Cart;

final class ApiCartFake implements CartInterface
{
    /**
     * @var array<int, int>
     */
    private array $items = [];

    public function add(int $productId, int $quantity = 1): void
    {
        $this->items[$productId] = ($this->items[$productId] ?? 0) + max(1, $quantity);
    }

    public function remove(int $productId): void
    {
        unset($this->items[$productId]);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getQuantity(int $productId): int
    {
        return $this->items[$productId] ?? 0;
    }
}
