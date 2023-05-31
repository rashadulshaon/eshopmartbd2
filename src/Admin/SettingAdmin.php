<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

final class SettingAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('create')
            ->remove('delete')
            ->remove('show')
            ->remove('export');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('key', null, [
                'label' => 'Setting'
            ])
            ->add('value', null, [
                'label' => 'Value',
                'editable' => true
            ]);
    }
}
