<?php

namespace App\Admin;

use App\Form\ProductImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class ProductAdmin extends AbstractAdmin
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
            ->add('brand', ModelType::class, [
                'required' => false
            ])
            ->add('category', ModelType::class)
            ->add('wholesalePrice', IntegerType::class, ['required' => false])
            ->add('regularPrice', TextType::class, ['required' => true])
            ->add('price')
            ->add('isStockOut')
            ->add('firstImage', ProductImageType::class, ['required' => $this->isCurrentRoute('create')])
            ->add('secondImage', ProductImageType::class, ['required' => false])
            ->add('thirdImage', ProductImageType::class, ['required' => false])
            ->add('summary', TextareaType::class, ['required' => false])
            ->add('description', CKEditorType::class, ['required' => false]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('name')
            ->add('wholesalePrice')
            ->add('regularPrice')
            ->add('price')
            ->add('isStockOut')
            ->add('brand')
            ->add('category');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('name')
            ->add('brand')
            ->add('category')
            ->add('wholesalePrice')
            ->add('regularPrice')
            ->add('price')
            ->add('isStockOut')
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
            ->add('name')
            ->add('brand')
            ->add('category')
            ->add('wholesalePrice')
            ->add('regularPrice')
            ->add('price')
            ->add('isStockOut')
            ->add('summary')
            ->add('description');
    }
}
