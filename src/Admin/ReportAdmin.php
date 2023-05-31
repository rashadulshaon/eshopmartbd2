<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

final class ReportAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('create')
            ->remove('edit')
            ->remove('delete')
        ;
    }
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('product')
            ->add('totalOrder')
            ->add('pendingOrder')
            ->add('onHoldOrder')
            ->add('paymentPendingOrder')
            ->add('shippedOrder')
            ->add('canceledOrder')
            ->add('completedOrder')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('product')
            ->add('totalOrder')
            ->add('pendingOrder')
            ->add('onHoldOrder')
            ->add('paymentPendingOrder')
            ->add('shippedOrder')
            ->add('canceledOrder')
            ->add('completedOrder')
        ;
    }
}
