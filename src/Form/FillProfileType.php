<?php

namespace App\Form;

use App\Form\Data\FillProfileData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class FillProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstname',
                Type\TextType::class,
                [
                    'label' => 'First Name',
                ]
            )
            ->add(
                'lastname',
                Type\TextType::class,
                [
                    'label' => 'Last Name',
                ]
            )
            ->add(
                'save',
                Type\SubmitType::class,
                [
                    'label' => 'Save',
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
            'data_class' => FillProfileData::class
        ]);
    }
}
