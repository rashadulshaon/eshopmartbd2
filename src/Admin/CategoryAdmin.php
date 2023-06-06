<?php

namespace App\Admin;

use App\Form\ProductImageType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class CategoryAdmin extends AbstractAdmin
{
    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues['_sort_order'] = 'DESC';
        $sortValues['_sort_by'] = 'id';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('image', ProductImageType::class, ['required' => false]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->add('name');
        $list->add(ListMapper::NAME_ACTIONS, null, [
            'actions' => [
                'edit' => [],
                'delete' => [],
            ]
        ]);
    }
}
