<?php

namespace App\EntityListener;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class OrderEntityListener
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function postPersist(Order $order, LifecycleEventArgs $event)
    {
        $this->setStatusReport($order);
    }

    public function preRemove(Order $order, LifecycleEventArgs $event)
    {
        foreach ($order->getOrderItems() as $item) {
            $product = $item->getProducts()[0];
            $orderItems = $product->getOrderItems();
            $report = $product->getReport();

            $totalOrders = 0;
            $pendingOrders = 0;
            $onHoldOrders = 0;
            $paymentPendingOrders = 0;
            $shippedOrders = 0;
            $canceledOrders = 0;
            $completedOrders = 0;

            foreach ($orderItems as $orderItem) {
                $order = $orderItem->getProductOrder();
                $totalOrders += 1;

                switch ($order->getOrderState()) {
                    case 'Pending':
                        $pendingOrders += 1;
                        break;
                    case 'On Hold':
                        $onHoldOrders += 1;
                        break;
                    case 'Shipped':
                        $shippedOrders += 1;
                        break;
                    case 'Canceled':
                        $canceledOrders += 1;
                        break;
                    case 'Completed':
                        $completedOrders += 1;
                        break;
                }

                if (!$order->isIsPaid()) {
                    $paymentPendingOrders += 1;
                }
            }

            $report->setTotalOrder($totalOrders);
            $report->setPendingOrder($pendingOrders);
            $report->setOnHoldOrder($onHoldOrders);
            $report->setPaymentPendingOrder($paymentPendingOrders);
            $report->setShippedOrder($shippedOrders);
            $report->setCanceledOrder($canceledOrders);
            $report->setCompletedOrder($completedOrders);
        }
    }

    public function postUpdate(Order $order, LifecycleEventArgs $event)
    {
        $this->setStatusReport($order);
    }

    private function setStatusReport(Order $order)
    {
        $this->em->flush();

        foreach ($order->getOrderItems() as $item) {
            $product = $item->getProducts()[0];
            $orderItems = $product->getOrderItems();
            $report = $product->getReport();

            $totalOrders = 0;
            $pendingOrders = 0;
            $onHoldOrders = 0;
            $paymentPendingOrders = 0;
            $shippedOrders = 0;
            $canceledOrders = 0;
            $completedOrders = 0;

            foreach ($orderItems as $orderItem) {
                $order = $orderItem->getProductOrder();
                $totalOrders += 1;

                switch ($order->getOrderState()) {
                    case 'Pending':
                        $pendingOrders += 1;
                        break;
                    case 'On Hold':
                        $onHoldOrders += 1;
                        break;
                    case 'Shipped':
                        $shippedOrders += 1;
                        break;
                    case 'Canceled':
                        $canceledOrders += 1;
                        break;
                    case 'Completed':
                        $completedOrders += 1;
                        break;
                }

                if (!$order->isIsPaid()) {
                    $paymentPendingOrders += 1;
                }
            }

            $report->setTotalOrder($totalOrders);
            $report->setPendingOrder($pendingOrders);
            $report->setOnHoldOrder($onHoldOrders);
            $report->setPaymentPendingOrder($paymentPendingOrders);
            $report->setShippedOrder($shippedOrders);
            $report->setCanceledOrder($canceledOrders);
            $report->setCompletedOrder($completedOrders);

            $this->em->flush();
        }
    }
}
