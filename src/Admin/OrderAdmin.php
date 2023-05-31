<?php

namespace App\Admin;

use App\Entity\Customer;
use App\Enum\OrderStateEnum;
use App\Form\OrderItemType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

final class OrderAdmin extends AbstractAdmin
{
    protected function configureBatchActions(array $actions): array
    {
        $actions['export'] = [
            'label'            => 'Export',
            'ask_confirmation' => false,
        ];

        return $actions;
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues['_sort_order'] = 'DESC';
        $sortValues['_sort_by'] = 'id';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('customer', ModelListType::class, [
                'class' => Customer::class
            ])
            ->add('shippingMethod')
            ->add('orderItems', CollectionType::class, [
                'entry_type' => OrderItemType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('note')
            ->add('discount')
            ->add('isPaid')
            ->add('isUnique')
            ->add('orderState', EnumType::class, [
                'class' => OrderStateEnum::class
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('customerName')
            ->add('customerPhone')
            ->add('address')
            ->add('shippingMethod')
            ->add('discount')
            ->add('subTotal')
            ->add('deliveryCost')
            ->add('totalCost')
            ->add('isPaid')
            ->add('placedAt')
            ->add('isUnique')
            ->add('orderState');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('customerName', null, [
                'editable' => true
            ])
            ->add('customerPhone', null, [
                'editable' => true
            ])
            ->add('address', null, [
                'editable' => true
            ])
            ->add('shippingMethod')
            ->add('orderItems')
            ->add('subTotal')
            ->add('deliveryCost')
            ->add('totalCost')
            ->add('isPaid', null, [
                'editable' => true
            ])
            ->add('placedAt')
            ->add('note', null, [
                'editable' => true
            ])
            ->add('statusDummy', 'choice', [
                'label' => 'Status',
                'choices' => $this->getStatusAsChoices(),
                'mapped' => false,
                'editable' => true
            ])
            ->add('isUnique', null, [
                'editable' => true
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('customer')
            ->add('customerPhone')
            ->add('address')
            ->add('shippingMethod')
            ->add('orderItems')
            ->add('discount')
            ->add('subTotal')
            ->add('deliveryCost')
            ->add('totalCost')
            ->add('isPaid')
            ->add('placedAt')
            ->add('note')
            ->add('isUnique')
            ->add('orderState', 'enum');
    }

    public function prePersist(object $order): void
    {
        $this->calculatePrice($order);
        $this->setCustomerInfo($order);
    }

    public function preUpdate(object $order): void
    {
        $this->calculatePrice($order);
    }

    private function calculatePrice(object $order)
    {
        $totalPrice = 0;

        foreach ($order->getOrderItems() as $item) {
            $item->setPrice($item->getProduct()->getPrice() * $item->getQuantity())    ;
            $totalPrice += $item->getPrice();
        }

        $order->setSubTotal($totalPrice - $order->getDiscount());
        $order->setDeliveryCost($order->getShippingMethod()->getCost());
        $order->setTotalCost($order->getSubTotal() + $order->getDeliveryCost());
    }

    private function setCustomerInfo(object $order)
    {
        if (!$order->getCustomer()) {
            throw new BadRequestException('Customer field should not be blank');
        }

        $order->setCustomerName($order->getCustomer()->getName());
        $order->setCustomerPhone($order->getCustomer()->getPhone());
        $order->setAddress($order->getCustomer()->getLocation());
    }

    private function getStatusAsChoices()
    {
        $cases = OrderStateEnum::cases();

        $choices = [];

        foreach ($cases as $case) {
            $choices[$case->value] = $case->name;
        }

        return $choices;
    }
}
