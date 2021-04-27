<?php

namespace App\Form;

use App\Form\Data\SettingsData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class UserSettingsType
 * @package App\Form
 */
class UserSettingsType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('first_name', Type\TextType::class, ['label' => 'First Name'])
            ->add('last_name', Type\TextType::class, ['label' => 'Last Name'])
            ->add('email', Type\EmailType::class, ['label' => 'Email'])
            ->add('email_notify', Type\CheckboxType::class, ['label' => 'Receive Email notification with new Alerts', 'required' => false])
            ->add('phone', Type\TextType::class, ['label' => 'Phone'])
            ->add('phone_notify', Type\CheckboxType::class, ['label' => 'Receive notification with new Alerts', 'required' => false])
            ->add('country', Type\CountryType::class, ['label' => 'Country', 'required' => false])
            ->add('city', Type\TextType::class, ['label' => 'City', 'required' => false])
            ->add('state', Type\TextType::class, ['label' => 'State', 'required' => false])
            ->add('zip_code', Type\TextType::class, ['label' => 'Zip Code', 'required' => false])
            ->add('address', Type\TextType::class, ['label' => 'Address', 'required' => false])
            ->add('avatar', Type\HiddenType::class, ['label' => null, 'required' => false])
            ->add('upload_file', Type\FileType::class, ['label' => null, 'required' => false])
            ->add('submit', Type\SubmitType::class, ['label' => 'Save changes']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SettingsData::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
