<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\OrderStateEnum;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

final class OrderAdminController extends CRUDController
{
    public function __construct(
        private OrderRepository $orderRepo,
        private EntityManagerInterface $em
    ) {}

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
            $row['Note'] = $item->getOrderItems()[0]->getQuantity() . ' x ' . $item->getOrderItems()[0]->getProducts()[0]->getName();
            ;

            $orders[] = $row;
        }

        return $this->json($orders);
    }

    public function batchActionSteadFastExport(ProxyQueryInterface $query)
    {
        $data = $query->execute();
        $orders = [];

        foreach ($data as $item) {
            $row = [];

            $row['Invoice'] = $item->getId();
            $row['Name'] = $item->getCustomerName();
            $row['Address'] = $item->getAddress();
            $row['Phone'] = $item->getCustomerPhone();
            $row['Amount'] = $item->getTotalCost();
            $row['Note'] = $item->getOrderItems()[0]->getQuantity() . ' x ' . $item->getOrderItems()[0]->getProducts()[0]->getName();
            $row['Contact Name'] = $item->getCustomerName();
            $row['Contact Phone'] = $item->getCustomerPhone();

            $orders[] = $row;
        }

        return $this->json($orders);
    }

    public function batchActionPathaoExport(ProxyQueryInterface $query)
    {
        $data = $query->execute();
        $orders = [];

        foreach ($data as $item) {
            $row = [];

            $row['ItemType(*)'] = 'parcel';
            $row['MerchantOrderId'] = $item->getId();
            $row['RecipientName(*)'] = $item->getCustomerName();
            $row['RecipientPhone(*)'] = $item->getCustomerPhone();
            $row['RecipientCity(*)'] = '';
            $row['RecipientAddress(*)'] = $item->getAddress();
            $row['AmountToCollect(*)'] = $item->getTotalCost();

            $qty = 0;

            foreach ($item->getOrderItems() as $lineItem) {
                $qty += $lineItem->getQuantity();
            }

            $row['ItemQuantity(*)'] = $qty;
            $row['ItemDesc'] = $item->getOrderItems()[0]->getProducts()[0]->getName();

            $orders[] = $row;
        }

        return $this->json($orders);
    }

    public function batchActionInvoice(ProxyQueryInterface $query)
    {
        $data = $query->execute();

        return $this->render('order/invoice.html.twig', [
            'orders' => $data
        ]);
    }

    public function batchActionMarkAsShipped(ProxyQueryInterface $query)
    {
        $data = $query->execute();

        foreach ($data as $item) {
            $item->setOrderState(OrderStateEnum::Shipped);
        }

        $this->em->flush();

        $this->addFlash('success', 'Order status has been set to shipped.');

        return $this->redirectToRoute('admin_app_order_list');
    }

    public function batchActionMarkAsCompleted(ProxyQueryInterface $query)
    {
        $data = $query->execute();

        foreach ($data as $item) {
            $item->setOrderState(OrderStateEnum::Completed);
        }

        $this->em->flush();

        $this->addFlash('success', 'Order status has been set to completed.');

        return $this->redirectToRoute('admin_app_order_list');
    }

    public function batchActionMarkAsCanceled(ProxyQueryInterface $query)
    {
        $data = $query->execute();

        foreach ($data as $item) {
            $item->setOrderState(OrderStateEnum::Canceled);
        }

        $this->em->flush();

        $this->addFlash('success', 'Order status has been set to canceled.');

        return $this->redirectToRoute('admin_app_order_list');
    }
}
