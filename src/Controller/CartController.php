<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $itemsInCart = [];
    private $checkoutItem = [];
    private $session;
    private $productRepo;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepo)
    {
        $this->session = $requestStack->getSession();
        $this->itemsInCart = $this->session->get('itemsInCart', []);
        $this->checkoutItem = $this->session->get('checkoutItem', []);
        $this->session->set('checkoutItem', $this->checkoutItem);
        $this->productRepo = $productRepo;
    }

    #[Route('/cart_update/{action}/{id}/{quantity}/{fromCheckout}', name: 'app_cart_update')]
    public function cart_update(string $action, Product $product, int $quantity = 1, $fromCheckout = false): Response
    {
        if (!$product->isIsStockOut()) {
            //send server side reply so cart page price can be updated by each product row
            switch ($action) {
                case 'add':
                    if (isset($this->itemsInCart[$product->getId()])) {
                        $this->itemsInCart[$product->getId()]['quantity'] += $quantity;
                    } else {
                        $this->itemsInCart[$product->getId()] = ['product' => $product, 'quantity' => $quantity];
                    }
                    break;
                case 'substract':
                    if ($this->itemsInCart[$product->getId()]['quantity'] > 1) {
                        $this->itemsInCart[$product->getId()]['quantity'] -= $quantity;
                    }
                    break;
                case 'update':
                    if ($this->itemsInCart[$product->getId()]['quantity'] > 1) {
                        $this->itemsInCart[$product->getId()]['quantity'] = $quantity;
                    }
                    break;
                case 'remove':
                    unset($this->itemsInCart[$product->getId()]);
            }

            $this->session->set('itemsInCart', $this->itemsInCart);

            if ($fromCheckout) {
                return $this->redirectToRoute('app_checkout');
            }
        }

        if ($action == 'remove') {
            unset($this->itemsInCart[$product->getId()]);
            $this->session->set('itemsInCart', $this->itemsInCart);
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/checkout_update/{action}/{id}/{quantity}', name: 'app_checkout_update')]
    public function checkout_update(string $action, Product $product, int $quantity = 1): Response
    {

        switch ($action) {
            case 'add':
                if (isset($this->checkoutItem[$product->getId()])) {
                    $this->checkoutItem[$product->getId()]['quantity'] += $quantity;
                } else {
                    $this->checkoutItem[$product->getId()] = ['product' => $product, 'quantity' => $quantity];
                }

                $this->session->set('checkoutItem', $this->checkoutItem);
                break;
            case 'substract':
                if ($this->checkoutItem[$product->getId()]['quantity'] > 1) {
                    $this->checkoutItem[$product->getId()]['quantity'] -= $quantity;
                }

                $this->session->set('checkoutItem', $this->checkoutItem);

                break;
            case 'update':
                if ($this->checkoutItem[$product->getId()]['quantity'] > 1) {
                    $this->checkoutItem[$product->getId()]['quantity'] = $quantity;
                }

                $this->session->set('checkoutItem', $this->checkoutItem);

                break;
            case 'remove':
                unset($this->checkoutItem[$product->getId()]);
        }

        return $this->redirectToRoute('app_checkout');
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(): Response
    {
        $items = array_map(
            fn ($item) =>
            [
                'product' => $this->productRepo->findOneBy(['id' => $item['product']->getId()]),
                'quantity' => $item['quantity'],
                'price' => $item['product']->getPrice() * $item['quantity'],
            ],
            $this->itemsInCart
        );

        $totalPrice = array_reduce($items, fn ($prev, $item) => $prev + $item['price'], 0);

        // Out of stock product check
        $stockOut = false;

        foreach ($items as $item) {
            if ($item['product']->isIsStockOut()) {
                $stockOut = true;
            }
        }

        return $this->render('cart/index.html.twig', [
            'items' => $items,
            'totalPrice' => $totalPrice,
            'stockOut' => $stockOut
        ]);
    }
}
