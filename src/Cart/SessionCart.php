<?php

namespace App\Cart;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionCart implements CartInterface
{
    private const SESSION_KEY = 'cart';

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);
        $items = $this->getItems();
        $items[$productId] = ($items[$productId] ?? 0) + $quantity;

        $this->getSession()->set(self::SESSION_KEY, $items);
    }

    public function remove(int $productId): void
    {
        $items = $this->getItems();
        unset($items[$productId]);

        $this->getSession()->set(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        $this->getSession()->remove(self::SESSION_KEY);
    }

    public function getItems(): array
    {
        $items = $this->getSession()->get(self::SESSION_KEY, []);
        $normalized = [];

        foreach ($items as $productId => $quantity) {
            $productId = (int) $productId;
            $quantity = (int) $quantity;

            if ($productId > 0 && $quantity > 0) {
                $normalized[$productId] = $quantity;
            }
        }

        return $normalized;
    }

    public function getQuantity(int $productId): int
    {
        return $this->getItems()[$productId] ?? 0;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
