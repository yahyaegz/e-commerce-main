<?php

namespace App\Controller;

use App\Cart\CartHandler;
use App\Cart\CartInterface;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('', name: 'app_cart', methods: ['GET'])]
    public function index(CartHandler $cartHandler): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartHandler->getCart(),
        ]);
    }

    #[Route('/add/{id<\d+>}', name: 'app_cart_add', methods: ['POST'])]
    public function add(int $id, Request $request, ProductRepository $productRepository, CartInterface $cart): RedirectResponse
    {
        $product = $productRepository->find($id);

        if ($product === null) {
            throw $this->createNotFoundException('Product not found.');
        }

        if (!$this->isCsrfTokenValid('add-to-cart-'.$id, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid cart token.');
        }

        $cart->add($id, max(1, (int) $request->request->get('quantity', 1)));
        $this->addFlash('success', sprintf('%s was added to your cart.', $product->getName()));

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/remove/{id<\d+>}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(int $id, Request $request, CartInterface $cart): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('remove-from-cart-'.$id, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid cart token.');
        }

        $cart->remove($id);
        $this->addFlash('success', 'The product was removed from your cart.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clear(Request $request, CartInterface $cart): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('clear-cart', (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid cart token.');
        }

        $cart->clear();
        $this->addFlash('success', 'Your cart was cleared.');

        return $this->redirectToRoute('app_cart');
    }
}
