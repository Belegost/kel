<?php

namespace App\Form;

use App\Form\Data\ChangePasswordAPIData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class ChangePasswordAPIType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('password', Type\RepeatedType::class, [
                'type' => Type\PasswordType::class,
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Confirm Password'],
            ])
            ->add('submit', Type\SubmitType::class, ['label' => 'Continue']);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ChangePasswordAPIData::class,
        ]);
    }
}
