<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tourAt', DateType::class, [
                'label' => 'Date de votre visite',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('fullday', ChoiceType::class, [
                'label' => 'Type de billet',
                'multiple' => false,
                'expanded' => true,
                'choices' => ['Toute la journée' => true, 'L\'après-midi' => false],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
