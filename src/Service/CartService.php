<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ProductRepository $productRepository,
    ) {}

    public function add(int $productId, int $quantity = 1): void
    {
        $cart = $this->getRaw();
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        $this->save($cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->getRaw();
        unset($cart[$productId]);
        $this->save($cart);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($productId);
            return;
        }
        $cart = $this->getRaw();
        $cart[$productId] = $quantity;
        $this->save($cart);
    }

    public function clear(): void
    {
        $this->save([]);
    }

    /** Nombre total d'articles (somme des quantités) */
    public function getCount(): int
    {
        return array_sum($this->getRaw());
    }

    /** Total en euros */
    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->getItems() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    /**
     * Retourne les lignes du panier enrichies avec le Product.
     * @return array<array{product: Product, quantity: int, subtotal: float}>
     */
    public function getItems(): array
    {
        $cart = $this->getRaw();
        if (empty($cart)) {
            return [];
        }

        $products = $this->productRepository->findBy(['id' => array_keys($cart)]);
        $indexed  = [];
        foreach ($products as $product) {
            $indexed[$product->getId()] = $product;
        }

        $items = [];
        foreach ($cart as $id => $qty) {
            if (!isset($indexed[$id])) {
                continue;
            }
            $product = $indexed[$id];
            $items[] = [
                'product'  => $product,
                'quantity' => $qty,
                'subtotal' => round((float) $product->getPrice() * $qty, 2),
            ];
        }

        return $items;
    }

    private function getRaw(): array
    {
        return $this->requestStack->getSession()->get(self::SESSION_KEY, []);
    }

    private function save(array $cart): void
    {
        $this->requestStack->getSession()->set(self::SESSION_KEY, $cart);
    }
}
