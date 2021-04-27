<?php

namespace App\Form;

use App\Form\Data\ChangePasswordData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class ChangePasswordType
 * @package App\Form
 */
class ChangePasswordType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->setAction($options['action'])
            ->add('current_password', Type\PasswordType::class, ['label' => 'Current Password'])
            ->add('password', Type\PasswordType::class, ['label' => 'New Password'])
            ->add('repeatPassword', Type\PasswordType::class, ['label' => 'Confirm Password'])
            ->add('submit', Type\SubmitType::class, ['label' => 'Save changes']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ChangePasswordData::class,
            'attr' => ['novalidate'=>'novalidate'],
        ]);
    }
}
