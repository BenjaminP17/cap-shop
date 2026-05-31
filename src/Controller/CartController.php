<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    public function __construct(
        private readonly CartService $cart,
        private readonly ProductRepository $products,
    ) {}

    /** Page panier */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $this->cart->getItems(),
            'total' => $this->cart->getTotal(),
        ]);
    }

    /** Ajouter un produit (AJAX POST) */
    #[Route('/add/{id}', name: 'add', methods: ['POST'])]
    public function add(int $id, Request $request): JsonResponse
    {
        $product = $this->products->find($id);
        if (!$product) {
            return $this->json(['error' => 'Produit introuvable'], 404);
        }

        $qty = max(1, (int) ($request->toArray()['quantity'] ?? 1));
        $this->cart->add($id, $qty);

        return $this->json([
            'count'   => $this->cart->getCount(),
            'message' => '✓ Ajouté au panier',
            'product' => $product->getName(),
        ]);
    }

    /** Mettre à jour la quantité (AJAX POST) */
    #[Route('/update/{id}', name: 'update', methods: ['POST'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $qty = (int) ($request->toArray()['quantity'] ?? 0);
        $this->cart->updateQuantity($id, $qty);

        return $this->json([
            'count' => $this->cart->getCount(),
            'total' => number_format($this->cart->getTotal(), 2, ',', ' ') . ' €',
        ]);
    }

    /** Supprimer un produit (AJAX POST) */
    #[Route('/remove/{id}', name: 'remove', methods: ['POST'])]
    public function remove(int $id): JsonResponse
    {
        $this->cart->remove($id);

        return $this->json([
            'count' => $this->cart->getCount(),
            'total' => number_format($this->cart->getTotal(), 2, ',', ' ') . ' €',
        ]);
    }

    /** Vider le panier (AJAX POST) */
    #[Route('/clear', name: 'clear', methods: ['POST'])]
    public function clear(): JsonResponse
    {
        $this->cart->clear();

        return $this->json(['count' => 0, 'total' => '0,00 €']);
    }
}
