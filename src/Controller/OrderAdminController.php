<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\OrderRepository;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

final class OrderAdminController extends CRUDController
{
    public function __construct(
        private OrderRepository $orderRepo
    ) {
    }

    public function batchActionExport(ProxyQueryInterface $query)
    {
        $data = $query->execute();
        $orders = [];

        foreach ($data as $item) {
            $row = [];

            $row['ID'] = $item->getId();
            $row['Customer'] = $item->getCustomerName();
            $row['Phone Number'] = $item->getCustomerPhone();
            $row['Address'] = $item->getAddress();
            $row['Shipping Method'] = $item->getShippingMethod()->getTitle();
            $itemsStr = '';

            foreach ($item->getOrderItems() as $lineItem) {
                $itemsStr .= $lineItem->__toString() . ', ';
            }

            $row['Items'] = $itemsStr;
            $row['Sub Total'] = $item->getSubTotal();
            $row['Delivery Cost'] = $item->getDeliveryCost();
            $row['Total Cost'] = $item->getTotalCost();
            $row['Is Paid'] = $item->isIsPaid();
            $row['Placed At'] = $item->getPlacedAt()->format('Y-m-d');
            $row['Note'] = $item->getNote();

            $orders[] = $row;
        }

        return $this->json($orders);
    }
}
