<?php

namespace App\Twig;

use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StatisticExtension extends AbstractExtension
{
    public function __construct(
        private OrderRepository $orderRepo,
        private CustomerRepository $customerRepo,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getSales', [$this, 'getSales']),
        ];
    }

    public function getSales()
    {
        $lastOrder = $this->orderRepo->findBy([], ['placedAt' => 'DESC'], 1);

        if (count($lastOrder) > 0) {
            $lastOrderTime = $lastOrder[0]->getPlacedAt();
            $lastOrderText = $this->getTimeDifference($lastOrderTime, new DateTimeImmutable());
        }


        return [
            'lastOrderText' => $lastOrderText ?? null,
            'totalCustomers' => count($this->customerRepo->findAll()),
            'totalOrders' => count($this->orderRepo->findAll()),
            'pendingOrders' => count($this->orderRepo->findBy(['orderState' => 'Pending'])),
            'processingOrders' => count($this->orderRepo->findBy(['orderState' => 'Processing'])),
            'onHoldOrders' => count($this->orderRepo->findBy(['orderState' => 'On Hold'])),
            'paymentPendingOrders' => count($this->orderRepo->findBy(['isPaid' => false])),
            'shippedOrders' => count($this->orderRepo->findBy(['orderState' => 'Shipped'])),
            'canceledOrders' => count($this->orderRepo->findBy(['orderState' => 'Canceled'])),
            'completedOrders' => count($this->orderRepo->findBy(['orderState' => 'Completed'])),
        ];
    }

    private function getTimeDifference(DateTimeImmutable $previousTime, DateTimeImmutable $currentTime): string
    {
        $interval = $currentTime->diff($previousTime);

        $parts = [];
        if ($interval->y > 0) {
            $parts[] = $interval->y . ' year';
        }
        if ($interval->m > 0) {
            $parts[] = $interval->m . ' month';
        }
        if ($interval->d > 0) {
            $parts[] = $interval->d . ' day';
        }
        if ($interval->h > 0) {
            $parts[] = $interval->h . ' hour';
        }
        if ($interval->i > 0) {
            $parts[] = $interval->i . ' minute';
        }
        if ($interval->s > 0) {
            $parts[] = $interval->s . ' second';
        }

        $timeDifference = implode(' ', $parts);

        return $timeDifference . ' ago';
    }
}
