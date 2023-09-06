<?php

namespace App\Admin;

use App\Entity\Customer;
use App\Entity\Order;
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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class OrderAdmin extends AbstractAdmin
{
    protected function configureBatchActions(array $actions): array
    {
        $actions['export'] = [
            'label'            => 'Export',
            'ask_confirmation' => false,
        ];

        $actions['invoice'] = [
            'label'            => 'Generate Invoice',
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
                'class' => Customer::class,
                'row_attr' => [
                    'class' => 'col-md-12'
                ],
                'by_reference' => false
            ])
            ->add('customerName', null, [
                'label' => 'Billing Name',
                'row_attr' => [
                    'class' => 'col-md-6'
                ],
            ])
            ->add('customerPhone', null, [
                'label' => 'Billing Phone',
                'row_attr' => [
                    'class' => 'col-md-6'
                ],
            ])
            ->add('address', null, [
                'label' => 'Billing Address',
                'row_attr' => [
                    'class' => 'col-md-12'
                ],
            ])
            ->add('orderItems', CollectionType::class, [
                'entry_type' => OrderItemType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'row_attr' => [
                    'class' => 'col-md-12 orderItems'
                ]
            ])
            ->add('subTotal', IntegerType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'col-md-3'
                ],
                'attr' => [
                    'class' => 'subTotal'
                ],
                'disabled' => true
            ])
            ->add('shippingMethod', null, [
                'label' => 'Delivery Cost',
                'row_attr' => [
                    'class' => 'col-md-3'
                ],
                'attr' => [
                    'class' => 'shippingMethod'
                ],
            ])
            ->add('discount', null, [
                'row_attr' => [
                    'class' => 'col-md-3'
                ],
                'attr' => [
                    'class' => 'discount',
                    'min' => '0'
                ],
            ])
            ->add('grandTotal', IntegerType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'col-md-3'
                ],
                'attr' => [
                    'class' => 'grandTotal'
                ],
                'disabled' => true
            ])
            ->add('isPaid', null, [
                'row_attr' => [
                    'class' => 'col-md-2'
                ]
            ])
            ->add('note', null, [
                'row_attr' => [
                    'class' => 'col-md-12'
                ]
            ])
            ->add('orderState', EnumType::class, [
                'class' => OrderStateEnum::class,
                'row_attr' => [
                    'class' => 'col-md-12'
                ]
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
            ->add('orderDate')
            ->add('isUnique')
            ->add('orderState');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('customerName', null, [
                'editable' => true,
                'label' => 'Billing Name'
            ])
            ->add('customerPhone', null, [
                'editable' => true,
                'label' => 'Billing Phone'
            ])
            ->add('address', null, [
                'editable' => true,
                'label' => 'Billing Address'
            ])
            ->add('orderItems')
            ->add('totalCost')
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
            ->add('customerName', null, [
                'label' => 'Billing Name'
            ])
            ->add('customerPhone', null, [
                'label' => 'Billing Phone'
            ])
            ->add('address', null, [
                'label' => 'Billing Address'
            ])
            ->add('orderItems')
            ->add('subTotal')
            ->add('deliveryCost')
            ->add('discount')
            ->add('totalCost')
            ->add('isPaid')
            ->add('placedAt')
            ->add('note')
            ->add('isUnique')
            ->add('orderState', 'enum');
    }

    public function prePersist(object $order): void
    {
        $this->singleProductValidation($order);
        $this->calculatePrice($order);
    }

    public function preUpdate(object $order): void
    {
        $this->singleProductValidation($order);
        $this->calculatePrice($order);
    }

    private function calculatePrice(object $order)
    {
        $totalPrice = 0;

        foreach ($order->getOrderItems() as $item) {
            $item->setPrice($item->getProducts()[0]->getPrice() * $item->getQuantity());
            $totalPrice += $item->getPrice();
        }

        $order->setSubTotal($totalPrice - $order->getDiscount());
        $order->setDeliveryCost($order->getShippingMethod()->getCost());
        $order->setTotalCost($order->getSubTotal() + $order->getDeliveryCost());
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

    private function singleProductValidation(Order $order)
    {
        foreach($order->getOrderItems() as $item) {
            if (count($item->getProducts()) != 1) {
                throw new BadRequestHttpException('Select only 1 product per item');
            }
        }
    }
}
