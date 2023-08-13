<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\CheckoutType;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $em;
    private $session;
    private $productRepo;
    private $customerRepo;
    private $orderRepo;

    public function __construct(
        EntityManagerInterface $em,
        RequestStack $requestStack,
        ProductRepository $productRepo,
        CustomerRepository $customerRepo,
        OrderRepository $orderRepo,
    ) {
        $this->em = $em;
        $this->session = $requestStack->getSession();
        $this->productRepo = $productRepo;
        $this->orderRepo = $orderRepo;
        $this->customerRepo = $customerRepo;
    }

    #[Route('/checkout/{id}/{qty}', name: 'app_checkout')]
    public function checkout(Request $req, MailerInterface $mailer, ?Product $product, ?int $qty = 1): Response
    {
        $order = new Order();
        $checkoutForm = $this->createForm(CheckoutType::class, $order);
        $checkoutForm->handleRequest($req);

        if ($product) {
            $fromCart = false;

            if (null != $this->session->get('checkoutItem')) {
                foreach ($this->session->get('checkoutItem') as $checkoutItem) {
                    if ($checkoutItem['product']->getId() != $product->getId()) {
                        $productArr[$product->getId()] = ['product' => $product, 'quantity' => $qty];
                        $this->session->set('checkoutItem', $productArr);
                    }
                }
            } else {
                $productArr[$product->getId()] = ['product' => $product, 'quantity' => $qty];
                $this->session->set('checkoutItem', $productArr);
            }
        } else {
            $fromCart = true;
        }

        $items = $this->getItems($product);

        $totalPrice = 0;

        foreach ($items as $item) {
            // Out of stock product check
            if ($item->getProducts()[0]->isIsStockOut()) {
                $this->addFlash('warning', 'আপনার কাঙ্খিত প্রোডাক্টটি বর্তমানে আমাদের স্টকে নেই।');

                return $this->redirectToRoute('app_cart');
            }

            $totalPrice += $item->getPrice();
        }

        if ($checkoutForm->isSubmitted() && $checkoutForm->isValid()) {
            $items = $this->getItems($product);
            $totalPrice = 0;

            foreach ($items as $item) {
                $order->addOrderItem($item);
                $totalPrice += $item->getPrice();
            }

            $order->setSubTotal($totalPrice);
            $order->setDeliveryCost($order->getShippingMethod()->getCost());
            $order->setTotalCost($order->getSubTotal() + $order->getDeliveryCost());
            $checkoutFormData = $checkoutForm->getData();

            // Crating customer
            $previousCustomer = $this->customerRepo->findOneBy(['phone' => $checkoutFormData->getCustomerPhone()]);

            if ($previousCustomer) {
                $previousCustomer
                    ->setName($checkoutFormData->getCustomerName())
                    ->setLocation($checkoutFormData->getAddress());
                $order->setCustomer($previousCustomer);
            } else {
                $newCustomer = (new Customer())
                    ->setName($checkoutFormData->getCustomerName())
                    ->setPhone($checkoutFormData->getCustomerPhone())
                    ->setLocation($checkoutFormData->getAddress());

                $this->em->persist($newCustomer);

                $order->setCustomer($newCustomer);
            }

            $previousOrder = $this->orderRepo->findBy([], ['id' => 'DESC'], 1) ? $this->orderRepo->findBy([], ['id' => 'DESC'], 1)[0] : null;

            if ($previousOrder && $previousOrder->getCustomer() == $order->getCustomer()) {
                $order->setIsUnique(false);
                $previousOrder->setIsUnique(false);
            }

            $this->em->persist($order);

            $this->em->flush();

            $this->session->clear();

            // Sending email to admin
            $adminEmail = (new Email())
                ->from('system@eBuyHat.com')
                ->to('admin@eBuyHat.com')
                ->subject("{$order->getCustomerName()}'s Order in eBuyHat")
                ->text("{$order->getCustomerName()} has placed an order in eBuyHat. Order ID: #{$order->getId()}");

            $mailer->send($adminEmail);

            $this->session->set('lastOrderId', $order->getId());

            $params['id'] = $product ? $product->getId() : null;

            return $this->redirectToRoute('app_order_successful', $params);
        }

        return $this->render('order/checkout.html.twig', [
            'items' => $items,
            'totalPrice' => $totalPrice,
            'checkoutForm' => $checkoutForm->createView(),
            'fromCart' => $fromCart
        ]);
    }

    #[Route('/order_successful/{id}', name: 'app_order_successful')]
    public function orderSuccessful(?int $id = null): Response
    {
        return $this->render('order/successful.html.twig', [
            'orderId' => $this->session->get('lastOrderId')
        ]);
    }

    private function getItems($product)
    {
        if ($product) {
            $items = array_map(
                fn ($item) => (new OrderItem())
                    ->addProduct($this->productRepo->findOneBy(['id' => $item['product']->getId()]))
                    ->setQuantity($item['quantity'])
                    ->setPrice($item['product']->getPrice() * $item['quantity']),
                $this->session->get('checkoutItem') ?? []
            );
        } else {
            $items = array_map(
                fn ($item) => (new OrderItem())
                    ->addProduct($this->productRepo->findOneBy(['id' => $item['product']->getId()]))
                    ->setQuantity($item['quantity'])
                    ->setPrice($item['product']->getPrice() * $item['quantity']),
                $this->session->get('itemsInCart') ?? []
            );
        }

        return $items;
    }
}
