<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/checkout', name: 'checkout_')]
class CheckoutController extends AbstractController
{
    public function __construct(
        private readonly CartService $cart,
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        if ($this->cart->getCount() === 0) {
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('checkout/index.html.twig', [
            'items' => $this->cart->getItems(),
            'total' => $this->cart->getTotal(),
        ]);
    }

    #[Route('/process', name: 'process', methods: ['POST'])]
    public function process(Request $request): Response
    {
        if ($this->cart->getCount() === 0) {
            return $this->redirectToRoute('cart_index');
        }

        // Validation fictive — on accepte toujours
        $orderNumber = strtoupper(substr(md5(uniqid('cap', true)), 0, 8));
        $total       = $this->cart->getTotal();
        $shipping    = $total >= 60 ? 0.0 : 4.90;
        $items       = $this->cart->getItems();

        // On stocke la confirmation en session avant de vider le panier
        $request->getSession()->set('last_order', [
            'number'   => $orderNumber,
            'total'    => round($total + $shipping, 2),
            'shipping' => $shipping,
            'items'    => array_map(fn ($i) => [
                'name'     => $i['product']->getName(),
                'brand'    => $i['product']->getBrand(),
                'quantity' => $i['quantity'],
                'subtotal' => $i['subtotal'],
            ], $items),
            'email' => $request->request->get('email', ''),
        ]);

        $this->cart->clear();

        return $this->redirectToRoute('checkout_confirm');
    }

    #[Route('/confirmation', name: 'confirm', methods: ['GET'])]
    public function confirm(Request $request): Response
    {
        $order = $request->getSession()->get('last_order');
        if (!$order) {
            return $this->redirectToRoute('app_home');
        }

        // On ne retire pas la session pour que le rechargement fonctionne
        return $this->render('checkout/confirm.html.twig', [
            'order' => $order,
        ]);
    }
}
