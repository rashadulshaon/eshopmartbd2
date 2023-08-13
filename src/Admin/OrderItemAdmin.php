<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

final class OrderItemAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('list')
            ->remove('create')
            ->remove('edit')
            ->remove('delete');
    }
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('products')
            ->add('quantity')
            ->add('price')
        ;
    }
}
