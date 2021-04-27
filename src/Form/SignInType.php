<?php

namespace App\Form;

use App\Form\Data\SignInData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class SignInType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('username', Type\TextType::class, ['label' => 'Username'])
            ->add('password', Type\PasswordType::class, ['label' => 'Password'])
            ->add('keepsigned', Type\CheckboxType::class, ['label' => '', 'required'=>false]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => SignInData::class,
        ]);
    }
}
