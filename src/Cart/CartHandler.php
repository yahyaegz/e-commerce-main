<?php

namespace App\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;

final class CartHandler
{
    public function __construct(
        private readonly CartInterface $cart,
        private readonly ProductRepository $productRepository,
    ) {
    }

    /**
     * @return array{
     *     items: list<array{product: Product, quantity: int, unitPrice: float, lineTotal: float}>,
     *     total: float,
     *     totalQuantity: int
     * }
     */
    public function getCart(): array
    {
        $cartItems = $this->cart->getItems();

        if ($cartItems === []) {
            return [
                'items' => [],
                'total' => 0.0,
                'totalQuantity' => 0,
            ];
        }

        $products = $this->productRepository->findBy(['id' => array_keys($cartItems)]);
        $productsById = [];

        foreach ($products as $product) {
            $productsById[$product->getId()] = $product;
        }

        $rows = [];
        $total = 0.0;
        $totalQuantity = 0;

        foreach ($cartItems as $productId => $quantity) {
            if (!isset($productsById[$productId])) {
                continue;
            }

            $product = $productsById[$productId];
            $unitPrice = $product->getPrice();
            $lineTotal = $unitPrice * $quantity;

            $rows[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unitPrice' => $unitPrice,
                'lineTotal' => $lineTotal,
            ];

            $total += $lineTotal;
            $totalQuantity += $quantity;
        }

        return [
            'items' => $rows,
            'total' => $total,
            'totalQuantity' => $totalQuantity,
        ];
    }
}
