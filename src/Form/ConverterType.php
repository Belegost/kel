<?php

namespace App\Form;

use App\Form\Data\ConverterData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class ConverterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'from',
                Type\ChoiceType::class,
                [
//                    'label' => 'From:',
                    'label' => false,
                    'choices' => [
                        'BTC' => 'BTC',
                        'USDT' => 'USDT',
                    ],
                    'data' => 'BTC',
                    'placeholder' => 'From:',
                ]
            )
            ->add(
                'to',
                Type\ChoiceType::class,
                [
                    'label' => false,
                    'choices' => [
                        'BTC' => 'BTC',
                        'USDT' => 'USDT',
                    ],
                    'data' => 'USDT',
                    'placeholder' => 'To:',
                ]
            )
            ->add(
                'amount',
                Type\NumberType::class,
                [
                    'label' => 'Amount',
                ]
            )
            ->add(
                'save',
                Type\SubmitType::class,
                [
                    'label' => 'Convert',
                    'attr' => [
                        'class' => 'btn btn_blue',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConverterData::class,
        ]);
    }
}
