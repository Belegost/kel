<?php

namespace App\Form;

use Iso3166\Codes as IsoCodes;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('first_name', TextType::class, ['label' => 'First Name'])
            ->add('last_name', TextType::class, ['label' => 'Last Name'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            //->add('email_notify', CheckboxType::class, ['label' => 'Receive Email notification with new Alerts'])
            ->add('timezone', TimezoneType::class, ['label' => 'Timezone'])
            ->add('phone', TextType::class, ['label' => 'Phone'])
            ->add('phone_code', ChoiceType::class, [
                'label' => null,
                'choices' => IsoCodes::$phoneCodes,
                'mapped' => false
            ])
            //->add('phone_notify', CheckboxType::class, ['label' => 'Receive notification with new Alerts'])
            ->add('country', TextType::class, ['label' => 'Country'])
            ->add('city', TextType::class, ['label' => 'City'])
            ->add('state', TextType::class, ['label' => 'State'])
            ->add('zip_code', TextType::class, ['label' => 'Zip Code'])
            ->add('address', TextType::class, ['label' => 'Address'])
            ->add('avatar', FileType::class, ['label' => null, 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
