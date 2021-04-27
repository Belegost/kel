<?php

namespace App\Form;

use App\Form\Data\SignUpData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class SignUpType
 * @package App\Form
 */
class SignUpType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', Type\TextType::class, ['label' => 'Username'])
            ->add('password', Type\PasswordType::class, ['label' => 'Password'])
            ->add('repeatPassword', Type\PasswordType::class, ['label' => 'Repeat password'])
//            ->add('first_name', Type\TextType::class, ['label' => 'First Name'])
//            ->add('last_name', Type\TextType::class, ['label' => 'Last Name'])
            ->add('email', Type\EmailType::class, ['label' => 'Email'])
//            ->add('email_notify', Type\CheckboxType::class, ['label' => 'Receive Email notification with new Alerts', 'required' => false])
            ->add('phone', Type\TextType::class, ['label' => 'Phone', 'data' => $options['phone']])
//            ->add('phone_notify', Type\CheckboxType::class, ['label' => 'Receive notification with new Alerts', 'required' => false])
            ->add('avatar', Type\HiddenType::class, ['label' => null, 'required' => false])
            ->add('upload_file', Type\FileType::class, ['label' => null, 'required' => false])
            ->add('submit', Type\SubmitType::class, ['label' => 'Sign Up']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SignUpData::class,
            'attr' => ['novalidate'=>'novalidate'],
            'phone' => null,
        ]);
    }
}
