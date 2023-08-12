<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerName', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'নাম']])
            ->add('customerPhone', TelType::class, ['label' => false, 'attr' => [
                'placeholder' => 'ফোন নাম্বার',
                'pattern' => '[0-9]{11}'
            ]])
            ->add('shippingMethod', null, ['label' => false, 'attr' => ['placeholder' => 'শিপিং মেথড', 'class' => 'shippingMethod']])
            ->add('address', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'লোকেশন']])
            ->add('note', TextareaType::class, ['label' => false, 'attr' => ['placeholder' => 'নোট লিখতে পারেন (প্রোডাক্টের সাইজ/কালার ইত্যাদি)'], 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'order_item',
        ]);
    }
}
