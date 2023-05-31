<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('itemsInCart', [$this, 'itemsInCart']),
        ];
    }

    public function itemsInCart()
    {
        $totalItems = 0;
        $session = $this->requestStack->getSession();

        if ($session->get('itemsInCart')) {
            foreach ($session->get('itemsInCart') as $item) {
                $totalItems += $item['quantity'];
            }
        }

        return $totalItems;
    }
}
