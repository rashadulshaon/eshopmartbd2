<?php

namespace App\Form;

use App\Entity\OrderItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('products', null, [
                'multiple' => true,
                'row_attr' => [
                    'class' => 'itemProduct col-md-6'
                ]
            ])
            ->add('quantity', null, [
                'row_attr' => [
                    'class' => 'itemQuantity col-md-6',
                ],
                'attr' => [
                    'min' => "1"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class,
        ]);
    }
}
